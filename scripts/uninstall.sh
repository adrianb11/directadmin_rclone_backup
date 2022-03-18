#!/bin/sh

echo "Plugin Un-Installed!" #NOT! :)

ROOT_DIR="/usr/local/directadmin/plugins/rclone_backup"

mv $ROOT_DIR/admin/elements/conf/active/* $ROOT_DIR/admin/elements/conf/remove/
mv $ROOT_DIR/admin/elements/conf/inactive/* $ROOT_DIR/admin/elements/conf/remove/

sh $ROOT_DIR/admin/elements/scripts/manage_cron.sh -r

# Remove cron scheduler
COMMAND="/bin/sh $ROOT_DIR/admin/elements/scripts/manage_cron.sh -r # cron scheduler"
( crontab -l | grep -v -F "$COMMAND" ) | crontab -

exit 0
