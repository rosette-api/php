<?php

/**
 * Example code to call Rosette API to get a document's (located at given URL) category.
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
$categories_url_data = "https://onlocationvacations.com/2018/02/06/downton-abbey-exhibition-extended-april-2-nyc/";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$params->set('contentUri', $categories_url_data);

try {
    $result = $api->categories($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
