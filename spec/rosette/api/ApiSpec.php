<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApiSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('randomkey');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('rosette\api\Api');
    }

    public function it_instantiates_correctly()
    {
        $this->getServiceUrl()->shouldEndWith('/rest/v1/');
    }

    public function it_sets_gets_response_code()
    {
        $responseCode = 404;
        $this->setResponseCode($responseCode);
        $this->getResponseCode()->shouldBe($responseCode);
    }

    public function it_sets_gets_timeout()
    {
        $timeout = 120;
        $this->setTimeout($timeout);
        $this->getTimeout()->shouldBe($timeout);
    }

    public function it_sets_gets_debug()
    {
        $debug = true;
        $this->setDebug($debug);
        $this->getDebug()->shouldBe($debug);
        $debug = false;
        $this->setDebug($debug);
        $this->getDebug()->shouldBe($debug);
    }

    public function it_can_ping($request)
    {
        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API' ]);

        $this->setMockRequest($request);
        $this->ping()->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    public function it_gets_info($request)
    {
        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API' ]);

        $this->setMockRequest($request);
        $this->info()->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    public function it_calls_the_language_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\DocumentParameters');
        $params->content = 'Sample Data';

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API', 'versionChecked' => true ]);

        $this->setMockRequest($request);
        $this->language($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    public function it_calls_the_sentences_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\DocumentParameters');
        $params->content = 'Sample Data';

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API', 'versionChecked' => true ]);

        $this->setMockRequest($request);
        $this->sentences($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    public function it_calls_the_tokens_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\DocumentParameters');
        $params->content = 'Sample Data';

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API', 'versionChecked' => true ]);

        $this->setMockRequest($request);
        $this->tokens($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    public function it_calls_the_morphology_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\DocumentParameters');
        $params->content = 'Sample Data';

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API', 'versionChecked' => true ]);

        $this->setMockRequest($request);
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

    public function it_calls_the_entities_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\DocumentParameters');
        $params->content = 'Sample Data';

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API', 'versionChecked' => true ]);

        $this->setMockRequest($request);
        $this->entities($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    public function it_calls_the_entities_linked_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\DocumentParameters');
        $params->content = 'Sample Data';

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API', 'versionChecked' => true ]);

        $this->setMockRequest($request);
        $linked = true;
        $this->entities($params, $linked)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    public function it_calls_the_categories_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\DocumentParameters');
        $params->content = 'Sample Data';

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API', 'versionChecked' => true ]);

        $this->setMockRequest($request);
        $this->categories($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    public function it_calls_the_sentiment_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\DocumentParameters');
        $params->content = 'Sample Data';

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API', 'versionChecked' => true ]);

        $this->setMockRequest($request);
        $this->sentiment($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }
    
    public function it_calls_using_multipart($params, $request)
    {
        $params->beADoubleOf('\rosette\api\DocumentParameters');
        $params->loadDocumentFile('fakefile');

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API', 'versionChecked' => true ]);

        $this->setMockRequest($request);
        $this->sentiment($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    public function it_calls_the_name_translation_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\NameTranslationParameters');

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API', 'versionChecked' => true ]);

        $this->setMockRequest($request);
        $this->nameTranslation($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    public function it_calls_the_name_similarity_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\NameSimilarityParameters');

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API', 'versionChecked' => true ]);

        $this->setMockRequest($request);
        $this->nameSimilarity($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    public function it_calls_the_relationships_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\RelationshipsParameters');
        $params->contentUri = 'http://some.dummysite.com';

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API', 'versionChecked' => true ]);

        $this->setMockRequest($request);
        $this->relationships($params)->shouldHaveKeyWithValue('name', 'Rosette API');
    }

    public function it_fails_with_non_200_response($params, $request)
    {
        $params->beADoubleOf('\rosette\api\RelationshipsParameters');
        $params->contentUri = 'http://some.dummysite.com';

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(403);
        $request->getResponse()->willReturn([ 'message' => 'access to this resource denied', 'code' => 'forbidden' ]);

        $this->setMockRequest($request);
        $this->shouldThrow('rosette\api\RosetteException')->duringRelationships($params);
    }

    public function it_fails_check_version($params, $request)
    {
        $params->beADoubleOf('\rosette\api\RelationshipsParameters');
        $params->contentUri = 'http://some.dummysite.com';

        $request->beADoubleOf('rosette\api\RosetteRequest');
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => 'Rosette API', 'versionChecked' => false ]);

        $this->setMockRequest($request);
        $this->shouldThrow('rosette\api\RosetteException')->duringRelationships($params);
    }
}
