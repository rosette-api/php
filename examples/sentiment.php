<?php

/**
 * Example code to call Rosette API to get a document's sentiment from a local file.
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
$sentiment_file_data = "<html><head><title>New Ghostbusters Film</title></head><body><p>Original Ghostbuster Dan Aykroyd, who also co-wrote the 1984 Ghostbusters film, couldn’t be more pleased with the new all-female Ghostbusters cast, telling The Hollywood Reporter, “The Aykroyd family is delighted by this inheritance of the Ghostbusters torch by these most magnificent women in comedy.”</p></body></html>";
$api = isset($options['url']) ? new Api($options['key'], $options['url']) : new Api($options['key']);
$params = new DocumentParameters();
$params->set('language', 'eng');
$content = $sentiment_file_data;
$temp = tmpfile();  // write above html content to a temp file
fwrite($temp, $content);
$params->loadDocumentFile(stream_get_meta_data($temp)['uri']);

try {
    $result = $api->sentiment($params);
    var_dump($result);
} catch (RosetteException $e) {
    error_log($e);
} finally {
    fclose($temp);  // clean up the temp file
}
