<?php

/**
 * Example code to call Rosette API to get topics (key phrases and concepts) in a piece of text.
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
$topics_data = "Lily Collins is in talks to join Nicholas Hoult in Chernin Entertainment and Fox Searchlight's J.R.R. Tolkien biopic Tolkien. Anthony Boyle, known for playing Scorpius Malfoy in the British play Harry Potter and the Cursed Child, also has signed on for the film centered on the famed author. In Tolkien, Hoult will play the author of the Hobbit and Lord of the Rings book series that were later adapted into two Hollywood trilogies from Peter Jackson. Dome Karukoski is directing the project.";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$params->set('content', $topics_data);

try {
    $result = $api->topics($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
