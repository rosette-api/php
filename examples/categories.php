<?php

/**
 * Example code to call Rosette API to get a document's (located at given URL) category.
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
$categories_text_data = "If you are a fan of the British television series Downton Abbey and you are planning to be in New York anytime before April 2nd, there is a perfect stop for you while in town.";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$params->set('content', $categories_text_data);

try {
    $result = $api->categories($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
