#!/bin/sh

echo "Changing permissions and ownership of files"
cd $ROOT_DIR

chmod 755 admin/*
chown diradmin:diradmin admin/*

echo "Plugin has been updated!" #NOT! :)

exit 0
