<?php

/**
 * Example code to call Rosette API to get similarity score for two addresses.
 **/
require_once dirname(__FILE__) . '/../vendor/autoload.php';
use rosette\api\Api;
use rosette\api\Address;
use rosette\api\AddressSimilarityParameters;
use rosette\api\RosetteException;

$options = getopt(null, array('key:', 'url::'));
if (!isset($options['key'])) {
    echo 'Usage: php ' . __FILE__ . " --key <api_key> --url=<alternate_url>\n";
    exit();
}

$addressSimilarityHouseno1 = "1600";
$addressSimilarityRoad1 = "Pennsylvania Ave NW";
$addressSimilarityCity1 = "Washington";
$addressSimilarityState1 = "DC";
$addressSimilarityPostcode1 = "20500";

$addressSimilarityHouseno2 = "160";
$addressSimilarityRoad2 = "Pennsilvana Ave";
$addressSimilarityCity2 = "Washington";
$addressSimilarityState2 = "D.C.";
$addressSimilarityPostcode2 = "20500";

$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new AddressSimilarityParameters(
    new Address(
        $addressSimilarityHouseno1,
        $addressSimilarityRoad1,
        $addressSimilarityCity1,
        $addressSimilarityState1,
        $addressSimilarityPostcode1
    ),
    new Address(
        $addressSimilarityHouseno2,
        $addressSimilarityRoad2,
        $addressSimilarityCity2,
        $addressSimilarityState2,
        $addressSimilarityPostcode2
    )
);

try {
    $result = $api->addressSimilarity($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
