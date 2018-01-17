<?php

/**
 * Example code to call Rosette API to get entities from a piece of text.
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
$entities_text_data = "The Securities and Exchange Commission today announced the leadership of the agency’s trial unit.  Bridget Fitzpatrick has been named Chief Litigation Counsel of the SEC and David Gottesman will continue to serve as the agency’s Deputy Chief Litigation Counsel. Since December 2016, Ms. Fitzpatrick and Mr. Gottesman have served as Co-Acting Chief Litigation Counsel.  In that role, they were jointly responsible for supervising the trial unit at the agency’s Washington D.C. headquarters as well as coordinating with litigators in the SEC’s 11 regional offices around the country.";
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
