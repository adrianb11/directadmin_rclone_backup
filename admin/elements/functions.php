<?php

/**
 * Read INI into array and return.
 * @param $file
 * @return array|false
 */
function ReadINI($file)
{
    $array = parse_ini_file($file, true);
    return $array;
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
    $fileArray = array_diff(scandir($dir), array('.', '..'));
    return $fileArray;
}


/**
 * Save POST to config_data variable
 * @param $config_data
 * @param $exclude
 * @param $post
 * @return mixed
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
 * @return bool
 *
 * Created by Terrorhawk -> https://github.com/Terrorhawk/Capri
 */
function getApi($cmd)
{
    if (isset($_SERVER["SERVER_PORT"])) {
        $_SERVER_PORT = $_SERVER["SERVER_PORT"];
    } else {
        $_SERVER_PORT = $_ENV["SERVER_PORT"];
    }

    if (isset($_SERVER["SESSION_KEY"])) {
        $_SESSION_KEY = $_SERVER["SESSION_KEY"];
    } else {
        $_SESSION_KEY = $_ENV["SESSION_KEY"];
    }

    if (isset($_SERVER["SESSION_ID"])) {
        $_SESSION_ID = $_SERVER["SESSION_ID"];
    } else {
        $_SESSION_ID = $_ENV["SESSION_ID"];
    }

    $headers = array();
    $headers["Host"] = "127.0.0.1:" . $_SERVER_PORT;
    $headers["Cookie"] = "session=" . $_SESSION_ID . "; key=" . $_SESSION_KEY;

    $send = "GET " . $cmd . " HTTP/1.1\r\n";
    foreach ($headers as $var => $value) $send .= $var . ": " . $value . "\r\n";
    $send .= "\r\n";

    $sIP = "127.0.0.1";

    // connect
    $res = @fsockopen($sIP, $_SERVER_PORT, $sock_errno, $sock_errstr, 5);
    if ($sock_errno || $sock_errstr) {
        return false;
    }
    if ($res) {
        // send query
        @fputs($res, $send, strlen($send));
        // get reply
        $result = '';
        while (!feof($res)) {
            $result .= fgets($res, 32768);
        }

        @fclose($res);

        // remove header
        $data = explode("\r\n\r\n", $result, 2);

        if (count($data) == 2) {
            return $data[1];
        } else {
            return false;
        }
    } else {
        return false;
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