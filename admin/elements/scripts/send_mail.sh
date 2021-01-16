#!/bin/bash

FROM="admin"
TO=""
SUBJECT=""
BODY=""

function show_usage() {
  printf "\n"
  printf "Usage:\n"
  printf "%s\n" "  $0 [options [parameters]]"
  printf "\n"
  printf "Options:\n"
  printf "%s\t\t%s\n" "  -f, --from" "Name of the sender (optional, default is admin)."
  printf "%s\t\t%s\n" "  -t, --to" "E-mail address of the receiver."
  printf "%s\t\t%s\n" "  -s, --subject" "Subject of the email."
  printf "%s\t\t%s\n" "  -b, --body" "Body of the email."
  printf "%s\t\t%s\n" "  -h, --help" "Print this help."
  printf "\n"

  return 0
}

while [ -n "$1" ]; do
  case $1 in
    --subject|-s)
      shift
      SUBJECT="$1"
    ;;
    --body|-b)
      shift
      BODY="$1"
    ;;
    --from|-f)
      shift
      FROM="$1"
    ;;
    --to|-t)
      shift
      TO="$1"
    ;;
    *)
      echo "ERROR: Invalid options."
      show_usage
      exit 1
    ;;
  esac
  shift || true
done

if [ -z "$TO" ] || [ -z "$SUBJECT" ] || [ -z "$BODY" ]; then
  echo "ERROR: Missing options."
  show_usage
  exit 1
fi

# sendmail command line optons:
# -i - do not treat lines starting with dot specially
# -t - read recipients lists from message headers: TO,CC,BCC
# -v - use verbose mode (describe what is happening)

/usr/sbin/sendmail -i -t << MESSAGE_END
From: $FROM
To: $TO 
Subject: $SUBJECT
$BODY
MESSAGE_END
