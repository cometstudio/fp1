<?php

/**
 * @param string $location
 * @param array $data
 * @param string $method
 * @param array $headers
 * @return array
 */
function HTTPRequest($location = '', $data = array(), $method = 'POST', $headers = array())
{
    logme(__FILE__.'::'.__METHOD__);

    $ch = curl_init();

    switch($method){
        default:
            $headers = array_merge($headers, array(
                'Content-type: application/x-www-form-urlencoded',
            ));

            $query = http_build_query($data);

            $location = $location.'?'.$query;
        break;
        case 'POST':
            logme('POST');
            $headers = array_merge($headers, array());

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            logme('Payload:');
            logme(print_r($data, true));
        break;
    }

    curl_setopt($ch, CURLOPT_URL, $location);
    curl_setopt($ch, CURLOPT_ENCODING, "UTF-8");
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $content = curl_exec($ch);
    $info = curl_getinfo($ch);

    logme('Request:');
    logme(print_r($info, true));
    logme('Response:');
    logme($content);

    curl_close($ch);

    if(empty($info) || ($info['http_code'] != 200)){
        logme('Error at HTTP request');
    }

    return array(
        'content'=>$content,
        'info'=>$info
    );
}

/**
 * @param string $row
 */
function logme($row = '')
{
    //file_put_contents('./log', $row.PHP_EOL.PHP_EOL, FILE_APPEND);
}

logme('Something happened');

$input = file_get_contents('php://input');

$query = $_REQUEST;

logme('Input:');
logme(print_r($input, true));

logme('Request:');
logme(print_r($query, true));