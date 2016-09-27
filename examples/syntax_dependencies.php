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
$syntaxDependenciesData = "Sony Pictures is planning to shoot a good portion of the new \"Ghostbusters\" in Boston as well.";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$content = $syntaxDependenciesData;
$params->set('content', $content);
$params->set('genre', 'social-media');

try {
    $result = $api->syntaxDependencies($params, false);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
