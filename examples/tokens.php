<?php

/**
 * Example code to call Rosette API to get tokens (words) in a piece of text.
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
$tokens_data = "北京大学生物系主任办公室内部会议";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$params->set('content', $tokens_data);

try {
    $result = $api->tokens($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
