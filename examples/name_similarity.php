<?php

/**
 * Example code to call Rosette API to get similarity score for two names.
 **/
require_once dirname(__FILE__) . '/../vendor/autoload.php';
use rosette\api\Api;
use rosette\api\Name;
use rosette\api\NameSimilarityParameters;
use rosette\api\RosetteException;

$options = getopt(null, array('key:', 'url::'));
if (!isset($options['key'])) {
    echo 'Usage: php ' . __FILE__ . " --key <api_key> --url=<alternate_url>\n";
    exit();
}
$name_similarity_data1 = "Michael Jackson";
$name_similarity_data2 = "迈克尔·杰克逊";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new NameSimilarityParameters(new Name($name_similarity_data1), new Name($name_similarity_data2));

try {
    $result = $api->nameSimilarity($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
