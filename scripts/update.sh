#!/bin/sh

echo "Changing permissions and ownership of files"
chmod 755 admin/*
chown diradmin:diradmin admin/*

echo "Plugin has been updated!" #NOT! :)

exit 0
