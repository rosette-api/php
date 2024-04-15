<?php
namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use rosette\api\RosetteConstants;

class ApiSpec extends ObjectBehavior
{
    private static $RosetteRequestFullClassName = 'rosette\api\RosetteRequest';
    private static $ResponseNameField = 'Rosette API';
    private static $DocumentParametersFullClassName = '\rosette\api\DocumentParameters';
    private static $SampleContent = 'Sample Data';
    private static $DummyUri = 'http://some.dummysite.com';
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
    public function it_constructs_user_agent()
    {
        $version = $this->getWrappedObject()->getBindingVersion();
        $uaString = 'RosetteAPIPHP/' . $version . '/' . phpversion();
        $this->getUserAgent()->shouldBe($uaString);
    }
    public function it_sets_gets_clears_url_params()
    {
        $this->setUrlParam("output", "Rosette");
        $this->setUrlParam("test", "foo");
        $this->getUrlParam("output")->shouldBe("Rosette");
        $this->getUrlParam("test")->shouldBe("foo");
        $this->clearUrlParams();
        $this->getUrlParam("output")->shouldBeNull();
        $this->getUrlParam("test")->shouldBeNull();
    }
    public function it_sets_gets_clears_single_url_param()
    {
        $this->setUrlParam("output", "Rosette");
        $this->getUrlParam("output")->shouldBe("Rosette");
        $this->clearUrlParams();
        $this->getUrlParam("output")->shouldBeNull();
    }
    public function it_sets_gets_clears_options()
    {
        $this->setOption("test", "foo");
        $this->getOption("test")->shouldBe("foo");
        $this->clearOptions();
        $this->getOption("test")->shouldBeNull();
    }
    public function it_sets_gets_clears_single_option()
    {
        $this->setOption("test", "foo");
        $this->getOption("test")->shouldBe("foo");
        $this->setOption("test", null);
        $this->getOption("test")->shouldBeNull();
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
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField ]);
        $this->setMockRequest($request);
        $this->ping()->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_gets_info($request)
    {
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField ]);
        $this->setMockRequest($request);
        $this->info()->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_language_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->content = ApiSpec::$SampleContent;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->language($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_sentences_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->content = ApiSpec::$SampleContent;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->sentences($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_tokens_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->content = ApiSpec::$SampleContent;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->tokens($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_morphology_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->content = ApiSpec::$SampleContent;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->morphology($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
        $facet = 'lemmas';
        $this->morphology($params, $facet)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
        $facet = 'parts-of-speech';
        $this->morphology($params, $facet)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
        $facet = 'compound-components';
        $this->morphology($params, $facet)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
        $facet = 'han-readings';
        $this->morphology($params, $facet)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_entities_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->content = ApiSpec::$SampleContent;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->entities($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_events_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->content = ApiSpec::$SampleContent;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->events($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_categories_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->content = ApiSpec::$SampleContent;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->categories($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_sentiment_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->content = ApiSpec::$SampleContent;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->sentiment($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }

    public function it_calls_using_multipart($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->loadDocumentFile('fakefile');
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->sentiment($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_name_translation_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\NameTranslationParameters');
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->nameTranslation($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_name_similarity_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\NameSimilarityParameters');
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->nameSimilarity($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_name_deduplication_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\NameDeduplicationParameters');
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->nameDeduplication($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_record_similarity_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\RecordSimilarityParameters');
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->recordSimilarity($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_relationships_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->contentUri = ApiSpec::$DummyUri;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->relationships($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_semantic_vectors_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->contentUri = ApiSpec::$DummyUri;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->semanticVectors($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_syntax_dependencies_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->contentUri = ApiSpec::$DummyUri;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->syntaxDependencies($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_transliteration_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->transliteration($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_topics_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->content = ApiSpec::$SampleContent;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->topics($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_similar_terms_endpoint($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->content = ApiSpec::$SampleContent;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->similarTerms($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_calls_the_address_similarity_endpoint($params, $request)
    {
        $params->beADoubleOf('\rosette\api\AddressSimilarityParameters');
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(200);
        $request->getResponse()->willReturn([ 'name' => ApiSpec::$ResponseNameField]);
        $this->setMockRequest($request);
        $this->addressSimilarity($params)->shouldHaveKeyWithValue('name', ApiSpec::$ResponseNameField);
    }
    public function it_fails_with_non_200_response($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->contentUri = ApiSpec::$DummyUri;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(403);
        $request->getResponse()->willReturn([ 'message' => 'access to this resource denied', 'code' => 'forbidden' ]);
        $this->setMockRequest($request);
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringRelationships($params);
    }
    public function it_fails_with_incorrectly_formatted_custom_header($params, $request)
    {
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringSetCustomHeader("test");
    }
    public function it_sets_gets_clears_customHeaders()
    {
        $this->setCustomHeader('X-RosetteAPI-test', 'true');
        $this->getCustomHeaders()->shouldBe(array('X-RosetteAPI-test' => 'true'));
        $this->clearCustomHeaders();
        $this->getCustomHeaders()->shouldBe(array());
    }
    public function it_fails_with_409_response($params, $request)
    {
        $params->beADoubleOf(ApiSpec::$DocumentParametersFullClassName);
        $params->contentUri = ApiSpec::$DummyUri;
        $request->beADoubleOf(ApiSpec::$RosetteRequestFullClassName);
        $request->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(true);
        $request->getResponseCode()->willReturn(409);
        $request->getResponse()->willReturn([ 'code' => 'incompatible version', 'message' => 'the version of client library used is not compatible with this server' ]);
        $this->setMockRequest($request);
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringRelationships($params);
    }
}
