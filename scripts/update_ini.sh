#!/bin/sh

ROOT_DIR="/usr/local/directadmin/plugins/rclone_backup"
DIRECTADMIN_INI=$ROOT_DIR/admin/elements/conf/directadmin.ini

# Update directadmin INI file
echo "Update ini file"
# Check if INI exists
if [ -f "$DIRECTADMIN_INI" ];
then
  echo "File exists"
  iniVersion=$(awk -F "=" '/version/ {print $2}' $DIRECTADMIN_INI | tr -d ' ')
  echo "Current version is $iniVersion"

  # Check if $iniVersion has been set.  Else version is less than 1.0.7
  if [ -z "$iniVersion" ]
  then
    echo "ini version is blank"
    iniVersion="1.0.6"
  fi

  # Run update for version 1.0.6
  if [ $iniVersion = "1.0.6" ]; then
    echo "updating ini file"
    #update
    sed -i '/^\[SETTINGS\]/a\ignore_certificate = false' $DIRECTADMIN_INI
    sed -i '/^\[SETTINGS\]/a\version = 1.0.7' $DIRECTADMIN_INI
    iniVersion=1.0.7
    echo "Updated ini to version 1.0.7"
  fi

  # Run next update.
fi