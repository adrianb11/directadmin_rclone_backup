#!/bin/bash
export PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin"
# Parameter $1: Config file (including path)

# -u: Treat unset variables as an error when substituting.
# -e: Exit immediately if a command exits with a non-zero status.
# -o pipefail: the return value of a pipeline is the status of the
#              last command to exit with a non-zero status.
set -o pipefail

# Root directory which contains the other directories.
# e.g. /usr/local/directadmin/plugins/rclone_backup
ROOT_DIR="/usr/local/directadmin/plugins/rclone_backup"
# Script directory which contains the CRON_CMD script.
SCRIPT_DIR="${ROOT_DIR}/admin/elements/scripts"
# Temp backup directory to store the backup files
TMP_BACKUP_DIR="/tmp/backup"
# Set to 0 to disable some output
VERBOSE=1

# Find mysqldump
MYSQLDUMP=$(which mysqldump)

# Include bash_ini_parser. Its an helper to parse INI-files.
. "$SCRIPT_DIR/bash_ini_parser/read_ini.sh"

# Define the valid options for RCLONE.structure
# U = Username
# D = Domain
# S = SubDomain
# o = Monthly
# e = Weekly
# a = Daily
# y = Year
# m = Month
# d = Day
_struct_opts="U D S o e a y m d"

# Define the valid options for DIRECTADMIN_DB.database_type
_db_opts="mysql postgresql mongodb"

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

# Helper function to check the validity of RCLONE.structure
# param $1: Pass the struct to this function
function check_struct() {
  if [ -z "$1" ]; then
    return 1
  fi
  tmp=$1
  for i in $(seq ${#tmp}); do
    flag=1
    for x in ${_struct_opts}; do
      if [ "${tmp:$i-1:1}" == "$x" ]; then
        flag=0
        continue
      fi
    done
    if [ "$flag" -eq 1 ]; then
      return 1
    fi
  done
}

# Helper function to check the validity of DIRECTADMIN_DB.database_type
# param $1: Pass the struct to this function
function check_db_type() {
  if [ -z "$1" ]; then
    return 1
  fi
  tmp=$1
  flag=1
  for db_type in ${_db_opts[@]}; do
    if [ "$db_type" == "$tmp" ]; then
      flag=0
      continue
    fi
  done
  if [ "$flag" -eq 1 ]; then
    return 1
  fi
}

# Convert the struct to a valid path
function struct_to_path() {
  if [ -z "$1" ]; then
    return 1
  fi
  path=""
  tmp=$1
  for i in $(seq ${#tmp}); do
    case ${tmp:$i-1:1} in
      U)
        if ! [ -z "$USERNAME" ]; then
          path="$path/$USERNAME"
        fi
      ;;
      D)
        if ! [ -z "$DOMAIN" ]; then
          path="$path/$DOMAIN"
        fi
      ;;
      S)
        if ! [ -z "$SUBDOMAIN" ]; then
          path="$path/$SUBDOMAIN"
        fi
      ;;
      o)
        path="$path/Monthly/$(date +%Y%m%d)"
      ;;
      e)
        path="$path/Weekly/$(date +%Y%m%d)"
      ;;
      a)
        path="$path/Daily/$(date +%Y%m%d)"
      ;;
      y)
        path="$path/$(date +%Y)"
      ;;
      m)
        path="$path/$(date +%m)"
      ;;
      d)
        path="$path/$(date +%d)"
      ;;
    esac
  done

  echo "${path#/}" # remove leading slash
}

# helper function to parse ini files
function parse_ini() {
  if [ -z "$1" ]; then
    return 1
  fi
  if [ -u "$2" ]; then
    read_ini "$1" -p CONF
  else
    read_ini "$1" "$2" -p CONF
  fi
  res=$?
  # Check for parsing errors or invalid INI files
  if ! [ "$res" -eq 0 ]; then
    send_mail " ERR" "Parsing error. Possibly the ini file is invalid"
    exit 1
  fi
}

# Do a directory backup
# Backup directadmin_backup_path and exclude directadmin_exclude_path
# e.g. username_domain_subdomain_**DATE OF BACKUP - ymd**_cron_id
function directory_backup() {
  # subdomain isn't mandatory, so check this
  if [ -z "$SUBDOMAIN" ]; then
    backup_file="${USERNAME}_${DOMAIN}_$(date "+%Y%m%d")_${CRON_ID}.${COMPRESSION_ALG}"
  else
    backup_file="${USERNAME}_${DOMAIN}_${SUBDOMAIN}_$(date "+%Y%m%d")_${CRON_ID}.${COMPRESSION_ALG}"
  fi
  # Create temp backup directory backup/cron_id
  mkdir -p "$TMP_BACKUP_DIR/$CRON_ID"
  case ${COMPRESSION_ALG} in
    zip)
      log "INFO" "Backup data to zip-Archive"
      if [ -z "$EXCLUDE_PATH" ]; then
        zip -r "$TMP_BACKUP_DIR/$CRON_ID/$backup_file" "$BACKUP_PATH" >/dev/null 2>&1
      else
        zip -r "$TMP_BACKUP_DIR/$CRON_ID/$backup_file" "$BACKUP_PATH" -x "${EXCLUDE_PATH%/}/*" >/dev/null 2>&1 # remove slash at the end
      fi
      ret=$?
      if ! [ "$ret" -eq 0 ]; then
        send_mail " ERR" "Something went wrong while directory backup; zip: RETURN $ret"
        clear_backup
        exit 1
      fi
    ;;
    gzip)
      log "INFO" "Backup data to gzip-Archive"
      if [ -z "$EXCLUDE_PATH" ]; then
        tar -zvcf "$TMP_BACKUP_DIR/$CRON_ID/$backup_file" "$BACKUP_PATH" >/dev/null 2>&1
      else
        tar -zvcf "$TMP_BACKUP_DIR/$CRON_ID/$backup_file" --exclude="$EXCLUDE_PATH" "$BACKUP_PATH" >/dev/null 2>&1
      fi
      ret=$?
      if ! [ "$ret" -eq 0 ]; then
        send_mail " ERR" "Something went wrong while directory backup; tar (gzip): RETURN $ret"
        clear_backup
        exit 1
      fi
    ;;
    tar)
      log "INFO" "Backup data to tar-Archive"
      if [ -z "$EXCLUDE_PATH" ]; then
        tar -cvf "$TMP_BACKUP_DIR/$CRON_ID/$backup_file" "$BACKUP_PATH" >/dev/null 2>&1
      else
        tar -cvf "$TMP_BACKUP_DIR/$CRON_ID/$backup_file" --exclude="$EXCLUDE_PATH" "$BACKUP_PATH" >/dev/null 2>&1
      fi
      ret=$?
      if ! [ "$ret" -eq 0 ]; then
        send_mail " ERR" "Something went wrong while directory backup; tar: RETURN $ret"
        clear_backup
        exit 1
      fi
    ;;
  esac
  log "INFO" "Created directory backup $backup_file"
}

function database_backup() {
  # subdomain isn't mandatory, so check this
  if [ -z "$SUBDOMAIN" ]; then
    backup_file="${USERNAME}_${DOMAIN}_$(date "+%Y%m%d")_${CRON_ID}"
  else
    backup_file="${USERNAME}_${DOMAIN}_${SUBDOMAIN}_$(date "+%Y%m%d")_${CRON_ID}"
  fi
  # Create temp backup directory backup/cron_id
  mkdir -p "$TMP_BACKUP_DIR/$CRON_ID"
  case ${DB_TYPE} in
    mysql)
      log "INFO" "Backup mysql database"
      backup_file="$backup_file.sql"

        if [ "$MY_CONF_ENABLED" -eq 1 ]; then
          log "INFO" "Use .my.cnf"
          $MYSQLDUMP -u "$MY_CONF_LOGIN" "$DB_NAME" > "$TMP_BACKUP_DIR/$CRON_ID/${backup_file}"
        else
          $MYSQLDUMP "$DB_NAME" > "$TMP_BACKUP_DIR/$CRON_ID/${backup_file}"
        fi

      ret=$?
      if ! [ "$ret" -eq 0 ]; then
        send_mail " ERR" "Something went wrong while backup database; mysqldump: RETURN $ret"
        clear_backup
        exit 1
      fi
    ;;
    postgresql)
      log "INFO" "Backup postgresql database"
      backup_file="$backup_file.pg"
      pg_dump "$DB_NAME" -f "$TMP_BACKUP_DIR/$CRON_ID/${backup_file}"
      ret=$?
      if ! [ "$ret" -eq 0 ]; then
        send_mail " ERR" "Something went wrong while backup database; pg_dump: RETURN $ret"
        clear_backup
        exit 1
      fi
    ;;
    mongodb)
      log "INFO" "Backup mongodb database"
      backup_file="$backup_file.mongo"
      mongodump --db "$DB_NAME" > "$TMP_BACKUP_DIR/$CRON_ID/${backup_file}"
      ret=$?
      if ! [ "$ret" -eq 0 ]; then
        send_mail " ERR" "Something went wrong while backup database; mongodump: RETURN $ret"
        clear_backup
        exit 1
      fi
    ;;
  esac
  case ${COMPRESSION_ALG} in
    zip)
      log "INFO" "Compress database to zip-Archive"
      zip -r "$TMP_BACKUP_DIR/$CRON_ID/$backup_file.zip" "$TMP_BACKUP_DIR/$CRON_ID/$backup_file" >/dev/null 2>&1
      ret=$?
      if ! [ "$ret" -eq 0 ]; then
        send_mail " ERR" "Something went wrong while compressing database; zip: RETURN $ret"
        clear_backup
        exit 1
      fi
    ;;
    gzip)
      log "INFO" "Compress database to gzip-Archive"
      tar -zvcf "$TMP_BACKUP_DIR/$CRON_ID/$backup_file.gz" "$TMP_BACKUP_DIR/$CRON_ID/$backup_file" >/dev/null 2>&1
      ret=$?
      if ! [ "$ret" -eq 0 ]; then
        send_mail " ERR" "Something went wrong while compressing database; zip: RETURN $ret"
        clear_backup
        exit 1
      fi
    ;;
    tar)
      log "INFO" "Compress database to tar-Archive"
      tar -cvf "$TMP_BACKUP_DIR/$CRON_ID/$backup_file.tar" "$TMP_BACKUP_DIR/$CRON_ID/$backup_file" >/dev/null 2>&1
      ret=$?
      if ! [ "$ret" -eq 0 ]; then
        send_mail " ERR" "Something went wrong while compressing database; zip: RETURN $ret"
        clear_backup
        exit 1
      fi
    ;;
  esac
  # Remove uncompress database backup
  rm "$TMP_BACKUP_DIR/$CRON_ID/$backup_file"
  log "INFO" "Created database backup $backup_file"
}

function clear_backup() {
  log "INFO" "Removing local backup files $TMP_BACKUP_DIR/$CRON_ID"
  # :? = Failing if TMP_BACKUP_DIR or CRON_ID is empty. Avoidance of rm -r /
  if [ -d "${TMP_BACKUP_DIR:?}/${CRON_ID:?}" ]; then
    rm -r "${TMP_BACKUP_DIR:?}/${CRON_ID:?}"
  fi
}

# Check if the parameter (path + filename) exist.
if [ -z "$1" ]; then
  send_mail " ERR" "Missing parameter when calling script"
  exit 1
fi

# Check if the file (parameter) exist.
if ! [ -f "$1" ]; then
  send_mail " ERR" "File $1 doesn't exist"
  exit 1
fi

#
# Parsing DA ini file
#
parse_ini "$ROOT_DIR/admin/elements/conf/directadmin.ini" "SETTINGS"
if [ -z "$CONF__ALL_VARS" ]; then
  send_mail " ERR" "No SETTINGS section found in directadmin INI"
  exit 1
fi
MY_CONF_ENABLED="$CONF__SETTINGS__my_conf_enabled"
MY_CONF_LOGIN="$CONF__SETTINGS__my_conf_login"

#
# Parsing the config file and
# store the values in variables.
# Check if variables are set.
#

# It is important to read the EMAIL as early as possible
# in order to be able to send an e-mail notification in
# case of an error.
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
    log " ERR" "Missing EMAIL.send_email_enabled"
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
log "INFO" "Parsing file $1"
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

log "INFO" "Parsing FILEHOST section"
parse_ini "$1" "FILEHOST"
if [ -z "$CONF__ALL_VARS" ]; then
  send_mail " ERR" "No FILEHOST section found"
  exit 1
fi
FILEHOST_ROOT_PATH="$CONF__FILEHOST__filehost_root_path"
if [ -z "$FILEHOST_ROOT_PATH" ]; then
  send_mail " ERR" "Missing FILEHOST options"
  exit 1
else
  log "INFO" "FILEHOST.filehost_root_path = $FILEHOST_ROOT_PATH"
fi

log "INFO" "Parsing RCLONE section"
parse_ini "$1" "RCLONE"
if [ -z "$CONF__ALL_VARS" ]; then
  send_mail " ERR" "No RCLONE section found"
  exit 1
fi
RCLONE_REMOTE="$CONF__RCLONE__remote"
RCLONE_STRUCT="$CONF__RCLONE__structure"
if [ -z "$RCLONE_REMOTE" ] || [ -z "$RCLONE_STRUCT" ]; then
  send_mail " ERR" "Missing RCLONE options"
  exit 1
else
  # Check if RCLONE.struct contains only allowed options
  check_struct "$RCLONE_STRUCT"
  res=$?
  if ! [ "$res" -eq 0 ]; then
    send_mail " ERR" "RCLONE.structure contains invalid options"
    exit 1
  fi
  log "INFO" "RCLONE.remote = $RCLONE_REMOTE"
  log "INFO" "RCLONE.structure = $RCLONE_STRUCT"
fi

log "INFO" "Parsing COMPRESSION section"
parse_ini "$1" "COMPRESSION"
if [ -z "$CONF__ALL_VARS" ]; then
  send_mail " ERR" "No COMPRESSION section found"
  exit 1
fi
COMPRESSION_ALG="${CONF__COMPRESSION__compression,,}" # to lowercase
if [ -z "$COMPRESSION_ALG" ]; then
  send_mail " ERR" "Missing COMPRESSION options"
  exit 1
else
  if [ "$COMPRESSION_ALG" == "zip" ] || [ "$COMPRESSION_ALG" == "gzip" ] || [ "$COMPRESSION_ALG" == "tar" ]; then
    log "INFO" "COMPRESSION.compression = $COMPRESSION_ALG"
  else
    send_mail " ERR" "Invalid COMPRESSION.compression defined"
    exit 1
  fi
fi

#
# Parsing the config file options WWW or DB and
# store the values in variables.
# Check if variables are set.
#
log "INFO" "Parsing DIRECTADMIN_WWW section"
parse_ini "$1" "DIRECTADMIN_WWW"
if [ -n "$CONF__ALL_VARS" ]; then
  USERNAME="$CONF__DIRECTADMIN_WWW__directadmin_user"
  DOMAIN="$CONF__DIRECTADMIN_WWW__directadmin_domain"
  SUBDOMAIN="$CONF__DIRECTADMIN_WWW__directadmin_subdomain"
  BACKUP_PATH="$CONF__DIRECTADMIN_WWW__directadmin_backup_path"
  EXCLUDE_PATH="$CONF__DIRECTADMIN_WWW__directadmin_exclude_path"
  # DIRECTADMIN_WWW.directadmin_exclude_path isn't mandatory, so dont check this here
  if [ -z "$USERNAME" ] || [ -z "$DOMAIN" ] || [ -z "$BACKUP_PATH" ]; then
    send_mail " ERR" "Missing DIRECTADMIN_WWW options"
    exit 1
  else
    if ! [ -d "$BACKUP_PATH" ]; then
      send_mail " ERR" "The Path in DIRECTADMIN_WWW.directadmin_backup_path doesn't exist"
      exit 1
    else
      log "INFO" "DIRECTADMIN_WWW.directadmin_user = $USERNAME"
      log "INFO" "DIRECTADMIN_WWW.directadmin_domain = $DOMAIN"
      if ! [ -z "$SUBDOMAIN" ]; then
        log "INFO" "DIRECTADMIN_WWW.directadmin_subdomain = $SUBDOMAIN"
      fi
      log "INFO" "DIRECTADMIN_WWW.directadmin_backup_path = $BACKUP_PATH"
      if ! [ -z "$EXCLUDE_PATH" ]; then
        log "INFO" "DIRECTADMIN_WWW.directadmin_exclude_path = $EXCLUDE_PATH"
      fi
    fi
  fi
  # Perform a directory backup
  directory_backup
else
  send_mail " ERR" "No DIRECTADMIN_WWW section found"
  exit 1
fi

log "INFO" "Parsing DIRECTADMIN_DB section"
parse_ini "$1" "DIRECTADMIN_DB"
if [ -n "$CONF__ALL_VARS" ]; then
  DB_NAME="$CONF__DIRECTADMIN_DB__database_name"
  DB_TYPE="${CONF__DIRECTADMIN_DB__database_type,,}" # to lowercase
  if ! [ -z "$DB_NAME" ] && ! [ -z "$DB_TYPE" ]; then
    check_db_type "$DB_TYPE"
    res=$?
    if ! [ "$res" -eq 0 ]; then
      send_mail " ERR" "DIRECTADMIN_DB.databases_type contains invalid options"
      clear_backup
      exit 1
    fi
    log "INFO" "DIRECTADMIN_DB.database_name = $DB_NAME"
    log "INFO" "DIRECTADMIN_DB.database_type = $DB_TYPE"
    # Perform a database backup
    database_backup
  else
    # When one of them is set then the other must be set
    if [[ -z "$DB_NAME" && ! -z "$DB_TYPE" ]] || [[ ! -z "$DB_NAME" && -z "$DB_TYPE" ]] ; then
      send_mail " ERR" "Missing DIRECTADMIN_DB options"
      clear_backup
      exit 1
    fi
  fi
else
  # DATABASE section isn't mandatory
  log "INFO" "No DIRECTADMIN_DB section found"
fi

#
# Prepare the filehost remote.
# Create directories on the remote
#
REMOTE_PATH="$(struct_to_path "$RCLONE_STRUCT")"
log "INFO" "Create path ${FILEHOST_ROOT_PATH%/}/${REMOTE_PATH} on remote $RCLONE_REMOTE"
rclone mkdir "$RCLONE_REMOTE:${FILEHOST_ROOT_PATH%/}/${REMOTE_PATH}"
res=$?
if ! [ "$res" -eq 0 ]; then
  send_mail " ERR" "Something went wrong while create path on remote ($RCLONE_REMOTE:${FILEHOST_ROOT_PATH%/}/${REMOTE_PATH})"
  clear_backup
  exit 1
fi

#
# Upload backup to remote
#
log "INFO" "Uploading backups to $RCLONE_REMOTE:${FILEHOST_ROOT_PATH%/}/${REMOTE_PATH}"
rclone copy "$TMP_BACKUP_DIR/$CRON_ID/" "$RCLONE_REMOTE:${FILEHOST_ROOT_PATH%/}/${REMOTE_PATH}"
res=$?
if ! [ "$res" -eq 0 ]; then
  send_mail " ERR" "Something went wrong while upload to remote ($RCLONE_REMOTE:${FILEHOST_ROOT_PATH%/}/${REMOTE_PATH})"
  clear_backup
  exit 1
fi

#
# Remove backup files
#
clear_backup

send_mail "  OK" "Job is completed"
exit 0
