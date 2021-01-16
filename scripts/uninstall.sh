#!/bin/sh

echo "Plugin Un-Installed!" #NOT! :)

ROOT_DIR="/usr/local/directadmin/plugins/rclone_backup"
CRON_FILE="/var/spool/cron/root"

mv $ROOT_DIR/admin/elements/conf/active/* $ROOT_DIR/admin/elements/conf/remove/
mv $ROOT_DIR/admin/elements/conf/inactive/* $ROOT_DIR/admin/elements/conf/remove/

sh $ROOT_DIR/admin/elements/scripts/manage_cron.sh -r

tmp=$(mktemp)
grep -v "$(basename "manage_cron.sh")" "$CRON_FILE" > "$tmp" && mv "$tmp" "$CRON_FILE"

exit 0
