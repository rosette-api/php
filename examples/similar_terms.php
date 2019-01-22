<?php

/**
 * Example code to call Rosette API to get related terms from sample text
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
$similar_terms_data = 'spy';
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$content = $similar_terms_data;
$params->set('content', $content);
$api->setOption('resultLanguages', array('spa', 'deu', 'jpn'));

try {
    $result = $api->similarTerms($params, false);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
