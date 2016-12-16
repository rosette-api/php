<?php

/**
 * Example code to call Rosette API to get lemmas for words in a piece of text.
 **/
require_once dirname(__FILE__) . '/../vendor/autoload.php';
use rosette\api\Api;
use rosette\api\DocumentParameters;
use rosette\api\RosetteConstants;
use rosette\api\RosetteException;

$options = getopt(null, array('key:', 'url::'));
if (!isset($options['key'])) {
    echo 'Usage: php ' . __FILE__ . " --key <api_key> --url=<alternate_url>\n";
    exit();
}
$morphology_lemmas_data = "The fact is that the geese just went back to get a rest and I'm not banking on their return soon";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$params->set('content', $morphology_lemmas_data);

try {
    $result = $api->morphology($params, RosetteConstants::$MorphologyOutput['LEMMAS']);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
