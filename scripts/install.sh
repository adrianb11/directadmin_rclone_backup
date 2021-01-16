#!/bin/sh
CRON_FILE="/var/spool/cron/root"
ROOT_DIR="/usr/local/directadmin/plugins/rclone_backup"

echo "Changing permissions and ownership of files"
cd $ROOT_DIR

chmod 755 admin/*
chown diradmin:diradmin admin/*

echo "Adding cron scheduler to crontab"
echo "*\5 * * * * /bin/sh $ROOT_DIR/admin/elements/scripts/manage_cron.sh -r # cron scheduler" >> "$CRON_FILE"

echo "Checking for installed software"
sh $ROOT_DIR/admin/elements/scripts/software_check.sh all v

echo "Plugin Installed!" #NOT! :)

exit 0
