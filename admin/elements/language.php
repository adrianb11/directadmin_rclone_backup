<?php

$lang = 'English';


/**
 * read ini file to get selected language and set in variable
 */
$ini_array = ReadINI("/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/directadmin.ini");
$lang = $ini_array['LANGUAGE']['language'];


/**
 * load language file
 */
if (file_exists('lang/' . $lang . '.php')) {
    require_once('lang/' . $lang . '.php');
} else {
    require_once('lang/English.php');
}