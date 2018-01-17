<?php

/**
 * Example code to call Rosette API to get sentences in a piece of text.
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
$sentences_data = "This land is your land. This land is my land, from California to the New York island; from the red wood forest to the Gulf Stream waters. This land was made for you and Me. As I was walking that ribbon of highway, I saw above me that endless skyway: I saw below me that golden valley: This land was made for you and me.";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$content = $sentences_data;
$params->set('content', $content);

try {
    $result = $api->sentences($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
