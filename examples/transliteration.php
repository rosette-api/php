<?php

/**
 * Example code to call Rosette API to transliterate content.
 **/
require_once dirname(__FILE__) . '/../vendor/autoload.php';
use rosette\api\Api;
use rosette\api\TransliterationParameters;
use rosette\api\RosetteException;

$options = getopt(null, array('key:', 'url::'));
if (!isset($options['key'])) {
    echo 'Usage: php ' . __FILE__ . " --key <api_key> --url=<alternate_url>\n";
    exit();
}
$transliteration_data = "معمر محمد أبو منيار القذاف";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new TransliterationParameters($transliteration_data, 'eng', 'Latn', 'ara', 'ara');

try {
    $result = $api->transliteration($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
