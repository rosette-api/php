<?php

/**
 * Example code to call Rosette API to translate a name from language to another.
 **/
require_once dirname(__FILE__) . '/../vendor/autoload.php';
use rosette\api\Api;
use rosette\api\NameTranslationParameters;
use rosette\api\RosetteException;

$options = getopt(null, array('key:', 'url::'));
if (!isset($options['key'])) {
    echo 'Usage: php ' . __FILE__ . " --key <api_key> --url=<alternate_url>\n";
    exit();
}
$name_translation_data = "معمر محمد أبو منيار القذاف";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new NameTranslationParameters();
$params->set('name', $name_translation_data);
$params->set('targetLanguage', 'eng');
$params->set('targetScript', 'Latn');
$params->set('targetScheme', 'IC');
$params->set('sourceLanguageOfOrigin', 'ara');
$params->set('sourceLanguageOfUse', 'ara');


try {
    $result = $api->nameTranslation($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
