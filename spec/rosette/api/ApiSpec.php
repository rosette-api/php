<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApiSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('randomkey');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('rosette\api\Api');
    }

    function it_instantiates_correctly()
    {
        $this->getServiceUrl()->shouldEndWith('/rest/v1/');
    }

    function it_sets_gets_response_code() {
        $responseCode = 404;
        $this->setResponseCode($responseCode);
        $this->getResponseCode()->shouldBe($responseCode);
    }

    function it_sets_gets_timeout() {
        $timeout = 120;
        $this->setTimeout($timeout);
        $this->getTimeout()->shouldBe($timeout);
    }

    function it_sets_gets_debug() {
        $debug = true;
        $this->setDebug($debug);
        $this->getDebug()->shouldBe($debug);
        $debug = false;
        $this->setDebug($debug);
        $this->getDebug()->shouldBe($debug);
    }

    function it_sets_gets_use_multi_part() {
        $useMultiPart = true;
        $this->setUseMultiPart($useMultiPart);
        $this->getUseMultiPart()->shouldBe($useMultiPart);
    }

    function it_can_ping() {
        $this->ping()->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    function it_gets_info() {
        $this->info()->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    function it_calls_the_language_endpoint(\rosette\api\DocumentParameters $params)
    {
        $this->language($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    function it_calls_the_sentences_endpoint(\rosette\api\DocumentParameters $params)
    {
        $this->sentences($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    function it_calls_the_tokens_endpoint(\rosette\api\DocumentParameters $params)
    {
        $this->tokens($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    function it_calls_the_morphology_endpoint(\rosette\api\DocumentParameters $params)
    {
        $this->morphology($params)->shouldHaveKeyWithValue('name', 'Rosette API');
        $facet = 'lemmas';
        $this->morphology($params, $facet)->shouldHaveKeyWithValue('name', 'Rosette API');
        $facet = 'parts-of-speech';
        $this->morphology($params, $facet)->shouldHaveKeyWithValue('name', 'Rosette API');
        $facet = 'compound-components';
        $this->morphology($params, $facet)->shouldHaveKeyWithValue('name', 'Rosette API');
        $facet = 'han-readings';
        $this->morphology($params, $facet)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    function it_calls_the_entities_endpoint(\rosette\api\DocumentParameters $params)
    {
        $this->entities($params)->shouldHaveKeyWithValue('name', 'Rosette API');
        $linked = true;
        $this->entities($params, $linked)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    function it_calls_the_categories_endpoint(\rosette\api\DocumentParameters $params)
    {
        $this->categories($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    function it_calls_the_sentiment_endpoint(\rosette\api\DocumentParameters $params)
    {
        $this->sentiment($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }
    
    function it_calls_using_multipart(\rosette\api\DocumentParameters $params)
    {
        $params->loadDocumentFile('fakefile');
        $this->sentiment($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    function it_calls_the_name_translation_endpoint(\rosette\api\NameTranslationParameters $params)
    {
        $this->nameTranslation($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    function it_calls_the_name_similarity_endpoint(\rosette\api\NameSimilarityParameters $params)
    {
        $this->nameSimilarity($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    function it_calls_the_relationships_endpoint(\rosette\api\RelationshipsParameters $params)
    {
        $this->relationships($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

}

namespace rosette\api;

// mock the curl_exec call - return the version checked response to satisfy checkVersion
function curl_exec($ch)
{
    // mock response
    // Note: The X's are set to a length to make the header = 200, which is necessary to force a correct
    //       boundary between the header and body.
    $mock_response = 'HTTP/1.1 200 OK'.PHP_EOL;
    $mock_response .= 'Content-Type: application/json'.PHP_EOL;
    $mock_response .= 'Date: Thu, 31 Mar 2016 13:12:00 GMT'.PHP_EOL;
    $mock_response .= 'Server: openresty/1.9.7.3'.PHP_EOL;
    $mock_response .= 'X-RosetteAPI-Request-Id: XXXXXXXXXXXXXXXXXXXXXXXX'.PHP_EOL;
    $mock_response .= 'Content-Length: 95'.PHP_EOL;
    $mock_response .= 'Connection: keep-alive'.PHP_EOL;
    $mock_response .= PHP_EOL;
    $mock_response .= '{"name":"Rosette API","version":"0.10.3","buildNumber":"","buildTime":"","versionChecked":true}';

    return $mock_response;
}

// mock the curl_getinfo call in order to return a valid code
function curl_getinfo($ch)
{
    return 200;
}
