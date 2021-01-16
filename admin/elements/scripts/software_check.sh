#!/bin/bash

DIRECTADMIN_INI="/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/directadmin.ini"

# Check if rclone is installed
if [[ $1 == "all" ||  $1 == "rclone" ]]; then
  if [[ $2 == "v" ]]; then
    echo "Check if RClone is installed"
  fi
  if ! command rclone version &> /dev/null
  then
    echo "ERROR: RClone could not be found."
    sed -i '/rclone_installed*/ c\rclone_installed = false' $DIRECTADMIN_INI
  else
    echo "RClone was found successfully"
    sed -i '/rclone_installed*/ c\rclone_installed = true' $DIRECTADMIN_INI
  fi
fi

# Check if zip is installed
if [[ $1 == "all" ||  $1 == "zip" ]]; then
  if [[ $2 == "v" ]]; then
    echo "Check if Zip is installed."
  fi
  if ! command -v zip &> /dev/null
  then
    echo "ERROR: Zip could not be found.  Option will not be available."
    sed -i '/zip_installed*/ c\zip_installed = false' $DIRECTADMIN_INI
  else
    echo "Zip was found successfully"
    sed -i '/zip_installed*/ c\zip_installed = true' $DIRECTADMIN_INI
  fi
fi

# Check if mysql is installed
if [[ $1 == "all" ||  $1 == "mysql" ]]; then
  if [[ $2 == "v" ]]; then
    echo "Check if MySQL is installed"
  fi
  if ! command -v mysql &> /dev/null
  then
    echo "ERROR: MySQL could not be found.  Option will not be available."
    sed -i '/mysql_installed*/ c\mysql_installed = false' $DIRECTADMIN_INI
  else
    echo "MySQL was found successfully"
    sed -i '/mysql_installed*/ c\mysql_installed = true' $DIRECTADMIN_INI
  fi
fi

# Check if postgresql is installed
if [[ $1 == "all" ||  $1 == "postgresql" ]]; then
  if [[ $2 == "v" ]]; then
    echo "Check if PostgreSQL is installed"
  fi
  if ! command -v psql &> /dev/null
  then
    echo "ERROR: PostgreSQL could not be found.  Option will not be available."
    sed -i '/postgresql_installed*/ c\postgresql_installed = false' $DIRECTADMIN_INI
  else
    echo "PostgreSQL was found successfully"
    sed -i '/postgresql_installed*/ c\postgresql_installed = true' $DIRECTADMIN_INI
  fi
fi

# Check if mongodb is installed
if [[ $1 == "all" ||  $1 == "mongodb" ]]; then
  if [[ $2 == "v" ]]; then
    echo "Check if MongoDB is installed"
  fi
  if ! command -v mongo &> /dev/null
  then
    echo "ERROR: MongoDB could not be found.  Option will not be available."
    sed -i '/mongodb_installed*/ c\mongodb_installed = false' $DIRECTADMIN_INI
  else
    echo "MongoDB was found successfully"
    sed -i '/mongodb_installed*/ c\mongodb_installed = true' $DIRECTADMIN_INI
  fi
fi
