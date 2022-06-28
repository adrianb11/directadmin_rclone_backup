#!/bin/sh

ROOT_DIR="/usr/local/directadmin/plugins/rclone_backup"
cd $ROOT_DIR

. /usr/local/directadmin/plugins/rclone_backup/admin/elements/scripts/update_ini.sh

echo "Creating required folders"
mkdir -p admin/elements/conf/pending
mkdir -p admin/elements/conf/active
mkdir -p admin/elements/conf/inactive
mkdir -p admin/elements/conf/remove

echo "Changing permissions and ownership of files"
chmod -R 755 admin/*
chown -R admin:admin admin/*

echo "Adding cron scheduler to crontab"
COMMAND="/bin/sh $ROOT_DIR/admin/elements/scripts/manage_cron.sh -r # cron scheduler"
JOB="*\5 * * * * $COMMAND"
( crontab -l | grep -v -F "$COMMAND" || : ; echo "$JOB" ) | crontab -

echo "Plugin has been updated!" #NOT! :)

exit 0
