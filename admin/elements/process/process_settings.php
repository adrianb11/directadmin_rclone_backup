<?php

/**
 * Run backup script.
 */
//TODO - Run backup script and select which cronjob to use.
if (isset($_SERVER['QUERY_STRING']) && isset($output['run_script'])) {
    if ($output['run_script'] === 'backup') {

    }
}


/**
 * Run manage cron script.
 */
if (isset($_SERVER['QUERY_STRING']) && isset($output['run_script'])) {
    if ($output['run_script'] === 'manage') {
        $output['run_script'] = shell_exec('sh /usr/local/directadmin/plugins/rclone_backup/admin/elements/scripts/manage_cron.sh -r');
    }
}


/**
 * Run software check script.
 */
if (isset($_SERVER['QUERY_STRING']) && isset($output['software'])) {
    $cmd = 'sh /usr/local/directadmin/plugins/rclone_backup/admin/elements/scripts/software_check.sh ' . $output['software'];
    $output['software'] = shell_exec($cmd);
}