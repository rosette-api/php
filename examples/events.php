<?php

/**
 * Example code to call Rosette API to get events from a piece of text.
 **/
require_once dirname(__FILE__) . '/../vendor/autoload.php';
use rosette\api\Api;
use rosette\api\DocumentParameters;
use rosette\api\RosetteException;

$options = getopt('', array('key:', 'url::'));
if (!isset($options['key'])) {
    echo 'Usage: php ' . __FILE__ . " --key <api_key> --url=<alternate_url>\n";
    exit();
}

$events_text_data = "Alice has a flight to Budapest. She has not booked a hotel.";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$content = $events_text_data;
$params->set('content', $content);

try {
    $result = $api->events($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}

$api->setOption('negation', 'BOTH');
try {
    $result = $api->events($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
