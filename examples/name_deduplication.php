<?php

/**
 * Example code to call Rosette API to deduplicate a list of names.
 **/
require_once dirname(__FILE__) . '/../vendor/autoload.php';
use rosette\api\Api;
use rosette\api\Name;
use rosette\api\NameDeduplicationParameters;
use rosette\api\RosetteException;

$options = getopt(null, array('key:', 'url::'));
if (!isset($options['key'])) {
    echo 'Usage: php ' . __FILE__ . " --key <api_key> --url=<alternate_url>\n";
    exit();
}
$name_dedupe_data = "Alice Terry,Alice Thierry,Betty Grable,Betty Gable,Norma Shearer,Norm Shearer,Brigitte Helm,Bridget Helem,Judy Holliday,Julie Halliday";

$dedup_array = array();
$threshold = 0.75;
foreach (explode(',', $name_dedupe_data) as $name) {
    array_push($dedup_array, new Name($name));
}
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new NameDeduplicationParameters($dedup_array, $threshold);

try {
    $result = $api->nameDeduplication($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
