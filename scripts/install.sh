#!/bin/sh
CRON_FILE="/var/spool/cron/root"
ROOT_DIR="/usr/local/directadmin/plugins/rclone_backup"

echo "Create ini file"
[ -e $ROOT_DIR/admin/elements/conf/directadmin.ini ] && echo "Config found" || mv $ROOT_DIR/admin/elements/conf/directadmin.ini.sample $ROOT_DIR/admin/elements/conf/directadmin.ini

chmod +x /usr/local/directadmin/plugins/rclone_backup/update_ini.sh
. /usr/local/directadmin/plugins/rclone_backup/scripts/update_ini.sh

cd $ROOT_DIR

echo "Creating required folders"
mkdir -p admin/elements/conf/pending
mkdir -p admin/elements/conf/active
mkdir -p admin/elements/conf/inactive

echo "Changing permissions and ownership of files"
chmod 755 admin/*
chown diradmin:diradmin admin/*

echo "Adding cron scheduler to crontab"
echo "*\5 * * * * /bin/sh $ROOT_DIR/admin/elements/scripts/manage_cron.sh -r # cron scheduler" >> "$CRON_FILE"

echo "Checking for installed software"
sh $ROOT_DIR/admin/elements/scripts/software_check.sh all v

echo "Plugin Installed!" #NOT! :)

exit 0
