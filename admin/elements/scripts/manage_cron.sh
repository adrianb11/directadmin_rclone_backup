#!/bin/bash

# -u: Treat unset variables as an error when substituting.
# -e: Exit immediately if a command exits with a non-zero status.
# -o pipefail: the return value of a pipeline is the status of the
#              last command to exit with a non-zero status.
set -o pipefail

# Root directory which contains the other directories.
# e.g. /usr/local/directadmin/plugins/rclone_backup
ROOT_DIR="/usr/local/directadmin/plugins/rclone_backup"
# Config directory which contains pending, active, inactive and remove directory.
CONF_DIR="${ROOT_DIR}/admin/elements/conf"
# Script directory which contains the CRON_CMD script.
SCRIPT_DIR="${ROOT_DIR}/admin/elements/scripts"
# crontab file. Should be adjusted.
# e.g /var/spool/cron/crontabs/<root>.
# Requires root privileges.
CRON_FILE="/var/spool/cron/root"
 # Script which is executed in the crontab.
CRON_CMD="backup_job.sh"

# Include bash_ini_parser. Its an helper to parse INI-files.
. "$SCRIPT_DIR/bash_ini_parser/read_ini.sh"

# Parameter using in this script.
# Can be overwritten via parameterization.
START=0
DRY_RUN=0
VERBOSE=0

CRON_ID=""

pendingfilecount=$(find "$CONF_DIR"/pending/ -type f | wc -l)
removefilecount=$(find "$CONF_DIR"/remove/ -type f | wc -l)

if [ "$pendingfilecount" -eq 0 ]; then
  echo "No pending jobs found to process"
fi
if [ "$removefilecount" -eq 0 ]; then
  echo "No jobs found to remove"
fi

function show_usage() {
  printf "\n"
  printf "Usage:\n"
  printf "%s\n" "  $0 [options [parameters]]"
  printf "\n"
  printf "Options:\n"
  printf "%s\t\t%s\n" "  -r, --run" "It should be avoided to execute the script accidentally. This flag is mandatory."
  printf "%s\t\t%s\n" "  -d, --dry-run" "Dry run. Do not edit crontab or moving files."
  printf "%s\t\t%s\n" "  -v, --verbose" "Print verbose output."
  printf "%s\t\t%s\n" "  -h, --help" "Print this help."
  printf "\n"

  return 0
}

# Helper function to log to stdout
function log() {
  if [ -z "$1" ] || [ -z "$2" ]; then
    return 1
  fi
  if [ "$VERBOSE" -eq 1 ] || [ "${1# }" == "ERR" ]; then
    echo "[ $(basename "$0") ] $1 cron_id ${CRON_ID:-UNKNOWN}: $2"
  fi
}

# Helper function to send mails
# @param $1: subject
# @param $2: message
function send_mail() {
  if [ -z "$EMAIL_ADDR" ]; then
    log "WARN" "E-mail isn't set. Couldn't send an email here"
    return 0
  fi
  # Fallback: If EMAIL_ENABLE isn't set yet, then send an email
  if ! [ -z "$EMAIL_ENABLED" ]; then
    # If disable return here
    if [ "$EMAIL_ENABLED" -eq 0 ]; then
      return 0
    fi
  fi
  if [ -z "$1" ] || [ -z "$2" ]; then
    return 1
  fi
  log "$1" "$2"
  "$SCRIPT_DIR"/send_mail.sh -t "$EMAIL_ADDR" -s "[ $(basename "$0") ] $1 cron_id ${CRON_ID:-UNKNOWN}" -b "$2"
}

# Parsing EMAIL and CRON_ID
# @param $1: filename + path of the configfile
function parse_main_info() {
  # Parsing EMAIL section
  # It is important to read the EMAIL as early as possible
  # in order to be able to send an e-mail notification in
  # case of an error.
  if [ -z "$1" ]; then
    return 1
  fi
  log "INFO" "Parsing EMAIL section"
  parse_ini "$1" "EMAIL"
  if [ -z "$CONF__ALL_VARS" ]; then
    log " ERR" "No EMAIL section found"
    # Couldn't send an email here
    exit 1
  fi
  EMAIL_ENABLED="$CONF__EMAIL__send_email_enabled"
  EMAIL_ADDR="$CONF__EMAIL__send_email_address"
  if [ -z "$EMAIL_ENABLED" ] || [ -z "$EMAIL_ADDR" ]; then
    if [ -z "$EMAIL_ENABLED" ]; then
      send_mail " ERR" "Missing EMAIL.send_email_enabled"
    fi
    if [ -z "$EMAIL_ADDR" ]; then
      log " ERR" "Missing EMAIL.send_email_address"
      # Couldn't send an email here
    else
      send_mail " ERR" "Missing EMAIL.send_email_enabled"
    fi
    exit 1
  else
    log "INFO" "EMAIL.send_email_enabled = $EMAIL_ENABLED"
    log "INFO" "EMAIL.send_email_address = $EMAIL_ADDR"
  fi

  # It is important to read the CRON_ID as early as possible
  # to be able to send an e-mail notification with the CRON_ID
  # in case of an error.
  log "INFO" "Parsing CRON section"
  parse_ini "$1" "CRON"
  if [ -z "$CONF__ALL_VARS" ]; then
    send_mail " ERR" "No CRON section found"
    exit 1
  fi
  CRON_ID="$CONF__CRON__cron_id"
  if [ -z "$CRON_ID" ]; then
    send_mail " ERR" "Missing CRON.cron_id"
    exit 1
  else
    log "INFO" "CRON.cron_id = $CRON_ID"
  fi

}

# helper function to delete a cronjob entry
# @param $1: Pass the path + filename of the config
function delete_cron_entry() {
  if [ -z "$1" ]; then
    return 1
  fi
  if [ "$DRY_RUN" -eq 0 ]; then
    ( crontab -l | grep -v -F "$file" ) | crontab -
    log "Deleted crontab entry."
  else
    log " DRY" "Deleted crontab entry."
  fi
}

# helper function to parse ini files
# @param $1: Pass the path + filename of the config
# @param $2: Pass the section name (optional)
function parse_ini() {
  if [ -z "$1" ]; then
    return 1
  fi
  if [ -z "$2" ]; then
    read_ini "$1" -p CONF
  else
    read_ini "$1" "$2" -p CONF
  fi
  res=$?
  # Check for parsing errors or invalid INI files
  if ! [ "$res" -eq 0 ]; then
    send_mail " ERR" "Parsing error. Possibly the ini file is invalid"
    return 1
  fi
}

# *.conf file in pending directory
# ACTIVE.active = true
# will be checked if an existing job exists and deleted if found
# the conf will be moved into the "active" folder,
# finally a new cronjob will be created
# @param $1: Pass the path + filename of the config
function activate_cron() {
  if [ -z "$1" ]; then
    return 1
  fi

  log "INFO" "Search for existing cron and delete it."
  delete_cron_entry "$file"

  # Read the CRON section from ini file
  parse_ini "$file" "CRON"
  if [ "$DRY_RUN" -eq 0 ]; then
    log "INFO" "Added cronjob to crontab."
    COMMAND="/bin/sh $SCRIPT_DIR/$CRON_CMD $CONF_DIR/active/$(basename $file) # cron_id $CONF__CRON__cron_id"
    JOB="$CONF__CRON__cron_output_minutes $CONF__CRON__cron_output_hours $CONF__CRON__cron_output_dom $CONF__CRON__cron_output_months $CONF__CRON__cron_output_dow $COMMAND"
    ( crontab -l | grep -v -F "$COMMAND" || : ; echo "$JOB" ) | crontab -
  else
    log "INFO" "Added cronjob to crontab."
  fi
  log "INFO" "Moving file $file to $CONF_DIR/active."
  if [ "$DRY_RUN" -eq 0 ]; then
    mv "$file" "$CONF_DIR/active/$(basename "$file")"
  else
    log "INFO" "Moved file $file to $CONF_DIR/active."
  fi
}

# *.conf file in pending directory
# ACTIVE.active = false
# will be checked if an existing job exists and deleted if found
# the conf will be moved into the "inactive" folder
# no cronjob will be created
# @param $1: Pass the path + filename of the config
function deactivate_cron() {
  if [ -z "$1" ]; then
    return 1
  fi

  log "INFO" "Search for existing cron and delete it."
  delete_cron_entry "$file"

  log "INFO" "Moving file $file to $CONF_DIR/inactive."
  if [ "$DRY_RUN" -eq 0 ]; then
    mv "$file" "$CONF_DIR/inactive/$(basename "$file")"
  else
    log " DRY" "Moved file $file to $CONF_DIR/inactive."
  fi
}

# *.conf file in remove directory
# will be checked if an existing job exists and deleted if found
# the conf will be deleted
# @param $1: Pass the path + filename of the config
function delete_cron() {
  if [ -z "$1" ]; then
    return 1
  fi

  log "INFO" "Search for existing cron and delete it."
  delete_cron_entry "$file"

  if [ "$DRY_RUN" -eq 0 ]; then
    rm "${file:?}"
  else
    log " DRY" "Deleted file $file."
  fi
}

if [ $# -eq 0 ]; then
  show_usage
  exit 0
fi

while [ -n "$1" ]; do
  case $1 in
    --run|-r)
      START=1
    ;;
    --dry-run|-d)
       DRY_RUN=1
    ;;
    --verbose|-v)
      VERBOSE=1
    ;;
    *)
      log " ERR" "Invalid options."
      show_usage
      exit 1
    ;;
  esac
  shift || true
done

if [ "$START" -eq 1 ]; then
  # Processing the pending directory
  for file in "$CONF_DIR"/pending/*.ini; do
    if [ -f "$file" ]; then
      log "INFO" "Processing config file $file."
      parse_main_info "$file"
      # Read the ACTIVE section from ini file
      parse_ini "$file" "ACTIVE"
      ACTIVE_STATE="$CONF__ACTIVE__active"
      if [ -z "$ACTIVE_STATE" ]; then
        send_mail " ERR" "Missing ACTIVE.active"
        exit 1
      else
        if [ "$ACTIVE_STATE" -eq 1 ]; then
          log "INFO" "Activate cronjob for $file."
          activate_cron "$file"
        elif [ "$ACTIVE_STATE" -eq 0 ]; then
          log "INFO" "Deactivate cronjob for $file."
          deactivate_cron "$file"
        else
          send_mail " ERR" "ACTIVE.active has an invalid value."
          exit 1
        fi
      fi
    fi
    CRON_ID="" # Reset cron_id
  done

  # Processing the remove directory
  for file in "$CONF_DIR"/remove/*.ini; do
    if [ -f "$file" ]; then
      log "INFO" "Processing config file $file."
      # It's not neccessary to parse email info
      # Just delete config file an existing cronjob
      # parse_main_info "$file"
      log "INFO" "Delete cronjob for $file."
      delete_cron "$file"
    fi
    # CRON_ID="" # Reset cron_id
  done
else
  show_usage
  exit 0
fi

exit 0
