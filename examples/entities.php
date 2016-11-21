<?php

/**
 * Example code to call Rosette API to get entities from a piece of text.
 **/
require_once dirname(__FILE__) . '/vendor/autoload.php';
use rosette\api\Api;
use rosette\api\DocumentParameters;
use rosette\api\RosetteException;

$options = getopt(null, array('key:', 'url::'));
if (!isset($options['key'])) {
    echo 'Usage: php ' . __FILE__ . " --key <api_key> --url=<alternate_url>\n";
    exit();
}
$entities_text_data = "Bill Murray will appear in new Ghostbusters film: Dr. Peter Venkman was spotted filming a cameo in Boston this… http://dlvr.it/BnsFfS";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$content = $entities_text_data;
$params->set('content', $content);
$params->set('genre', 'social-media');

try {
    $result = $api->entities($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
