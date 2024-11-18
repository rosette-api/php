<?php

/**
 * Example code to call Analytics API to get information such as version and build.
 **/
require_once dirname(__FILE__) . '/../vendor/autoload.php';
use rosette\api\Api;
use rosette\api\RosetteException;

$options = getopt('', array('key:', 'url::'));
if (!isset($options['key'])) {
    echo 'Usage: php ' . __FILE__ . " --key <api_key> --url=<alternate_url>\n";
    exit();
}

$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);

try {
    $result = $api->info();
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
