<?php

/**
 * Example code to call Rosette API to get text vectors from sample text
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
$semantic_vectors_data = "Cambridge, Massachusetts";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$content = $semantic_vectors_data;
$params->set('content', $content);

try {
    $result = $api->semanticVectors($params, false);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
