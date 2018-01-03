<?php

/**
 * Example code to call Rosette API to transliterate content.
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
$transliteration_data = "ana r2ye7 el gam3a el sa3a 3 el 3asr";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$params->set('content', $transliteration_data);

try {
    $result = $api->transliteration($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
