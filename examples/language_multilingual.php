<?php

/**
 * Example code to call Rosette API to detect possible languages for a piece of text.
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
$language_multilingual_data = "On Thursday, as protesters gathered in Washington D.C., the United States Federal Communications Commission under Chairman Ajit Pai voted 3-2 to overturn a 2015 decision, commonly called Net Neutrality, that forbade Internet service providers (ISPs) such as Verizon, Comcast, and AT&T from blocking individual websites or charging websites or customers more for faster load times.  Quatre femmes ont été nommées au Conseil de rédaction de la loi du Qatar. Jeudi, le décret royal du Qatar a annoncé que 28 nouveaux membres ont été nommés pour le Conseil de la Choura du pays.  ذكرت مصادر أمنية يونانية، أن 9 موقوفين من منظمة \"د هـ ك ب ج\" الذين كانت قد أوقفتهم الشرطة اليونانية في وقت سابق كانوا يخططون لاغتيال الرئيس التركي رجب طيب أردوغان.";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$content = $language_multilingual_data;
$params->set('content', $content);
$api->setCustomHeader('X-RosetteAPI-App', 'php-app');
$api->setOption('multilingual', 'true');

try {
    $result = $api->language($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
}
