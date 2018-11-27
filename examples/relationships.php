<?php

/**
 * Example code to call Rosette API to get a document's (located at given URL) relationships.
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
$relationships_text_data = "FLIR Systems is headquartered in Oregon and produces thermal imaging, night vision, and infrared cameras and sensor systems.  According to the SEC’s order instituting a settled administrative proceeding, FLIR entered into a multi-million dollar contract to provide thermal binoculars to the Saudi government in November 2008.  Timms and Ramahi were the primary sales employees responsible for the contract, and also were involved in negotiations to sell FLIR’s security cameras to the same government officials.  At the time, Timms was the head of FLIR’s Middle East office in Dubai.";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$params->set('content', $relationships_text_data);

try {
    $result = $api->relationships($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
