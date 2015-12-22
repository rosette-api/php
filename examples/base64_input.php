<?php

/**
 * Example code to call Rosette API to get entities from a piece of text.
 **/
require_once dirname(__FILE__) . '/../source/rosette/api/Api.php';
use rosette\api\Api;
use rosette\api\DocumentParameters;
use rosette\api\RosetteConstants;
use rosette\api\RosetteException;

$options = getopt(null, array('key:', 'url::'));
if (!isset($options['key'])) {
    echo 'Usage: php ' . __FILE__ . " --key <api_key> --url=<alternate_url>\n";
    exit();
}

$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$content = "Bill Murray will appear in new Ghostbusters film: Dr. Peter Venkman was spotted filming a cameo in Boston this… http://dlvr.it/BnsFfS ";
// There are two ways to process a base65 encoded string
$content = $params->loadDocumentString(base64_encode($content), RosetteConstants::$DataFormat['UNSPECIFIED']);
// or
// $params->set('content', $content);
// $params->contentType = RosetteConstants::$DataFormat['UNSPECIFIED'];

try {
    $result = $api->entities($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
