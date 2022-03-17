#!/bin/sh

ROOT_DIR="/usr/local/directadmin/plugins/rclone_backup"
DIRECTADMIN_INI=$ROOT_DIR/admin/elements/conf/directadmin.ini
cd $ROOT_DIR

echo "Creating required folders"
mkdir -p admin/elements/conf/pending
mkdir -p admin/elements/conf/active
mkdir -p admin/elements/conf/inactive

echo "Changing permissions and ownership of files"
chmod 755 admin/*
chown diradmin:diradmin admin/*

# Update directadmin INI file

# Check if INI exists
if [ -f "$DIRECTADMIN_INI" ];
then
  iniVersion=$(awk -F "=" '/version/ {print $2}' $ROOT_DIR/admin/elements/conf/directadmin.ini | tr -d ' ')

  # Check if $iniVersion has been set.  Else version is less than 1.0.7
  if [ -z "$iniVersion" ]
  then
    iniVersion=1.0.6
  fi

  # Run update for version 1.0.6
  if [ $iniVersion -eq 1.0.6 ]; then
    #update
    sed '/^\[SETTINGS\]/a\ignore_certificate = false' $DIRECTADMIN_INI
    sed '/^\[SETTINGS\]/a\version = 1.0.7' $DIRECTADMIN_INI
    iniVersion=1.0.7
    echo "Updated ini to version 1.0.7"
  fi

  # Run next update.
fi

echo "Plugin has been updated!" #NOT! :)

exit 0
