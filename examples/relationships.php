<?php

/**
 * Example code to call Rosette API to get a document's (located at given URL) relationships.
 **/
require_once dirname(__FILE__) . '/../vendor/autoload.php';
use rosette\api\Api;
use rosette\api\DocumentParameters;
use rosette\api\RosetteException;

$options = getopt(null, array('key:', 'url::'));
if (!isset($options['key'])) {
    echo 'Usage: php ' . __FILE__ . " --key <api_key> --url=<alternate_url>\n";
    exit();
}
$relationships_text_data = "Bill Gates, Microsoft's former CEO, is a philanthropist.";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$params->set('content', $relationships_text_data);
$api->setOption('accuracyMode', 'RECALL');

try {
    $result = $api->relationships($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
