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
 * @return bool|string
 */
function getApi($cmd)
{
    $ch = curl_init($_SERVER["SERVER_ADDR"] . ":" . $_SERVER["SERVER_PORT"] . $cmd);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, "session=" . $_SERVER["SESSION_ID"] . "; key=" . $_SERVER["SESSION_KEY"]);

    return curl_exec($ch);
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