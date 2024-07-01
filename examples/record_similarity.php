<?php

/**
 * Example code to call Rosette API to compare two lists of records and return a similarity score for each pair of records
 **/
require_once dirname(__FILE__) . '/../vendor/autoload.php';
use rosette\api\Api;
use rosette\api\RecordSimilarityParameters;
use rosette\api\RosetteException;

$options = getopt('', array('key:', 'url::'));
if (!isset($options['key'])) {
    echo 'Usage: php ' . __FILE__ . " --key <api_key> --url=<alternate_url>\n";
    exit();
}

$fields = array(
    "primaryName" => array("type" => "rni_name", "weight" => 0.5),
    "dob" => array("type" => "rni_date", "weight" => 0.2),
    "addr" => array("type" => "rni_address", "weight" => 0.5),
    "dob2" => array("type" => "rni_date", "weight" => 0.1)
);

$properties = array(
    "threshold" => 0.7,
    "includeExplainInfo" => true,
);

$records = array(
    "left" => array(
        array(
            "primaryName" => array("text" => "Ethan R", "language" => "eng", "script" => "Latn", "entityType" => "PERSON", "languageOfOrigin" => "eng"),
            "dob" => "1993-04-16",
            "addr" => "123 Roadlane Ave",
            "dob2" => array("date" => "04161993", "format" => "MMddyyyy")
        ),
        array(
            "dob" => array("date" => "1993-04-16"),
            "primaryName" => array("text" => "Evan R")
        )
    ),
    "right" => array(
        array(
            "dob" => array("date" => "1993-04-16"),
            "primaryName" => array("text" => "Seth R", "language" => "eng"),
        ),
        array(
            "dob" => array("date" => "1993-04-16"),
            "primaryName" => "Ivan R",
            "addr" => array("houseNumber" => "123", "road" => "Roadlane Ave"),
            "dob2" => array("date" => "1993/04/16")
        )

    )
);

$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new RecordSimilarityParameters($fields, $properties, $records);

try {
    $result = $api->recordSimilarity($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
