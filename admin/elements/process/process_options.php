<?php
$input_config_file = "/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/directadmin.ini";
$input_default_cron_file = "/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/DEFAULT.ini";
$exclude = array('INSTALLED');


/**
 * Load config into variables.
 */
$config_data = ReadINI($input_config_file);
$cron_data = ReadINI($input_default_cron_file);


/**
 * Save POST to config_data variable and default cron INI file.
 */
$config_data = WriteConfigData($config_data, $exclude, $output);
$cron_data = WriteConfigData($cron_data, $exclude, $output);


/**
 * Update config with new settings.
 */
WriteINI($config_data, $input_config_file);
WriteINI($cron_data, $input_default_cron_file);

$output["options_saved"] = true;