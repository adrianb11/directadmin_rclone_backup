<?php
$input_config_file = "/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/DEFAULT.ini";
$exclude = array('REFERENCE');

/**
 * Load config into variable.
 */
$config_data = ReadINI($input_config_file);

$output_config_file = "/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/pending/" . $output['cron_id'] . ".ini";


/**
 * Save POST to config_data variable.
 */
$config_data = WriteConfigData($config_data, $exclude, $output);


/**
 * Update config with new settings.
 */
if ($output['cron_state'] != "new") {
    unlink("/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/" . $output['cron_state'] . "/" . $output['cron_id'] . ".ini");
}


/**
 * Set variable to display alert.
 */
if ($output['cron_state'] != "new") {
    $output["cron_edited"] = $output['cron_id'];
} elseif ($output['cron_state'] == "new") {
    $output["cron_new"] = $output['cron_id'];
}

WriteINI($config_data, $output_config_file);