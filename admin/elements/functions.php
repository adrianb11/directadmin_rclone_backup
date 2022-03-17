<?php

/**
 * Read INI into array and return.
 * @param $file
 * @return array|false
 */
function ReadINI($file)
{
    return parse_ini_file($file, true);
}


/**
 * Write INI to $filepath using $data array and result.
 * @param $data
 * @param $filepath
 * @return bool|false|int
 */
function WriteINI($data, $filepath)
{
    $content = "";

    foreach ($data as $section => $values) {
        //append the section
        $content .= "[" . $section . "]\n";
        //append the values
        foreach ($values as $key => $value) {
            $content .= $key . "=" . $value . "\n";
        }
    }

    //write it into file
    if (!$handle = fopen($filepath, 'w')) {
        return false;
    }
    $success = fwrite($handle, $content);
    fclose($handle);
    return $success;
}


/**
 * Get directory contents into array and return.
 * @param $dir
 * @return array
 */
function GetDirContents($dir)
{
    return array_diff(scandir($dir), array('.', '..'));
}


/**
 * Save POST to config_data variable
 * @param $config_data
 * @param $exclude
 * @param $post
 * @return array
 */
function WriteConfigData($config_data, $exclude, $post)
{
    foreach ($config_data as $sectionKEY => $sectionVALUE) {

        //Check if KEY is in exclude list
        if (!in_array($sectionKEY, $exclude)) {
            foreach ($sectionVALUE as $elementKEY => $elementVALUE) {
                if (isset($post[$elementKEY])) {
                    $config_data[$sectionKEY][$elementKEY] = $post[$elementKEY];
                } else {
                    $config_data[$sectionKEY][$elementKEY] = '';
                }
            }
        }
    }
    return $config_data;
}


/**
 * Function to communicate with DirectAdmin API without username and password.
 * Uses session data so user must be logged in.
 * @param $cmd
 * @return string
 */
function getApi($cmd)
{
    $directadminarray = ReadINI("/usr/local/directadmin/plugins/rclone_backup/admin/elements/conf/directadmin.ini");

    $addr = "";

    if ($_SERVER["SSL"] === "1")
    {
        $addr .= "https://";
    }

    $addr .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $cmd;

    try {
        $ch = curl_init($addr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIE, "session=" . $_SERVER["SESSION_ID"] . "; key=" . $_SERVER["SESSION_KEY"]);

        if ($directadminarray["SETTINGS"]["ignore_certificate"] == true) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $content = curl_exec($ch);

        // Check the return value of curl_exec()
        if ($content === false) {
            throw new Exception(curl_error($ch), curl_errno($ch));
        }

        return curl_exec($ch);
    } catch(Exception $e) {
        trigger_error(sprintf(
            'Curl failed with error #%d: %s',
            $e->getCode(), $e->getMessage()),
        E_USER_ERROR);
    } finally {
        // Close curl handle unless it failed to initialize
        if (is_resource($ch)) {
            curl_close($ch);
        }
    }
}

/**
 * Shortcut to print an array.
 * @param $array
 */
function printArray($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}