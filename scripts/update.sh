#!/bin/sh

chmod +x update_ini.sh
. update_ini.sh

ROOT_DIR="/usr/local/directadmin/plugins/rclone_backup"
cd $ROOT_DIR

echo "Creating required folders"
mkdir -p admin/elements/conf/pending
mkdir -p admin/elements/conf/active
mkdir -p admin/elements/conf/inactive

echo "Changing permissions and ownership of files"
chmod 755 admin/*
chown diradmin:diradmin admin/*

echo "Plugin has been updated!" #NOT! :)

exit 0
