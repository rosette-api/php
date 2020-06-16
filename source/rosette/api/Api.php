<?php

/**
 * Api.
 *
 * Primary class for interfacing with the Rosette API
 *
 * @copyright 2015-2019 Basis Technology Corporation.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * @license   Apache http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 **/
namespace rosette\api;

/**
 * Class API.
 *
 * Api php Client Binding API; representation of a Api server.
 * Call instance methods upon this object to communicate with particular
 * Api server endpoints.
 * Aside from ping() and info(), most of the methods require the construction
 * of either a DocumentParameters object or an NameTranslationParameters object.  These
 * provide the content data that will be processed by the service.
 *
 * usage example: $api = new API($service_url, $user_key)
 *
 * @see _construct()
 */
class Api
{
    /**
     * Compatible server version.
     *
     * @var string
     */
    private static $binding_version = '1.14.4';

    /**
     * User key (required for Rosette API).
     *
     * @var null|string
     */
    private $user_key;

    /**
     * URL of the Rosette API (or test server).
     *
     * @var string
     */
    private $service_url;

    /**
     * HTTP headers for Rosette API.
     *
     * @var array
     */
    private $headers;

    /**
     * Parameters for the URL query.
     *
     * @var array
     */
    private $url_params;

    /**
     * Endpoint for the operation.
     *
     * @var null|string
     */
    private $subUrl;

    /**
     * response code
     *
     * @var string
     */
    private $response_code;

    /**
     * internal options array
     * @var array
     */
    private $options;

    /**
     * internal customHeaders array
     * @var array
     */
    private $customHeaders;

    /**
     * internal Request object
     * @var class
     */
    private $request;

    /**
     * Create an L{API} object.
     *
     * @param string $service_url URL of the Api API
     * @param string $user_key    An authentication string to be sent as user_key with
     *                            all requests.
     */
    public function __construct($user_key, $service_url = 'https://api.rosette.com/rest/v1/')
    {
        $this->user_key = $user_key;

        $this->headers = array('X-RosetteAPI-Key' => $user_key,
            'Content-Type' => 'application/json',
            'Accept-Encoding' => 'gzip',
            'User-Agent' => $this->getUserAgent(),
            'X-RosetteApi-Binding' => 'php',
            'X-RosetteAPI-Binding-Version' => $this->getBindingVersion());

        $this->setServiceUrl($service_url);
        $this->setDebug(false);
        $this->subUrl = null;
        $this->request = new RosetteRequest();
        $this->options = array();
        $this->url_params = array();
        $this->customHeaders = array();
    }

    /**
     * Returns the binding version
     */
    public function getBindingVersion()
    {
        return self::$binding_version;
    }

    /**
     * Returns the string used for User-Agent
     */
    public function getUserAgent()
    {
        return 'RosetteAPIPHP/' . $this->getBindingVersion() . '/' . phpversion();
    }

    /**
     * Sets on override Request for mocking purposes
     *
     * @param $requestObject
     */
    public function setMockRequest($requestObject)
    {
        $this->request = $requestObject;
    }

    /**
     * Sets the maximum retries for server connect
     *
     * @param $max_retries
     */
    public function setMaxRetries($max_retries)
    {
        $this->max_retries = $max_retries;
    }

    /**
     * Sets the millisecond sleep time between retries
     *
     * @param $max_retries
     */
    public function setMillisecondsBetweenRetries($ms_between_retries)
    {
        $this->ms_between_retries = $ms_between_retries;
    }

    /**
     * Returns response code.
     *
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->response_code;
    }

    /**
     * Sets the response code.
     *
     * @param mixed $response_code
     */
    public function setResponseCode($response_code)
    {
        $this->response_code = $response_code;
    }

    /**
     * Enables debug (more verbose output).
     *
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        $this->headers['X-RosetteAPI-Devel'] = $this->debug;
    }


    /**
     * Retrieves debug setting (more verbose output).
     *
     * @return bool
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Getter for the service_url.
     *
     * @return string
     */
    public function getServiceUrl()
    {
        return $this->service_url;
    }

    /**
     * Setter for the service_url.
     *
     * @param string
     */
    public function setServiceUrl($url)
    {
        $this->service_url = $url[strlen($url) - 1] === '/' ? $url : $url . '/';
    }

    /**
     * Setter for an additional query parameter to the Rosette API URL.
     *
     * @param string $param_name (e.g. output)
     * @param string $param_value (e.g. rosette)
     */
    public function setUrlParam($param_name, $param_value)
    {
        $this->url_params[$param_name] = $param_value;
    }

    /**
     * Getter for the URL parameter with the specified name
     *
     * @param string $param_name
     * @return string
     */
    public function getUrlParam($param_name)
    {
        return $this->getValueFromArray($param_name, $this->url_params);
    }

    /**
     * Clears all URL extension parameters.
     */
    public function clearUrlParams()
    {
        foreach ($this->url_params as $i => $value) {
            unset($this->url_params[$i]);
        }
        $this->url_params = array_values($this->url_params);
    }

    /**
     * Gets the value of the item with key $name in the array if it exists
     *
     * @param string $name
     * @param array $array
     * @return string
     */
    private function getValueFromArray($name, $array)
    {
        if (array_key_exists($name, $array)) {
            return $array[$name];
        } else {
            return null;
        }
    }

    /**
     * Getter for options
     *
     * @param string $name
     *
     * @return string
     */
    public function getOption($name)
    {
        return $this->getValueFromArray($name, $this->options);
    }

    /**
     * Setter for options
     *
     * @param string $name
     * @param string $value
     *
     */
    public function setOption($name, $value)
    {
        if (!is_null($value)) {
            $this->options[$name] = $value;
        } elseif (array_key_exists($name, $this->options)) {
            unset($this->options[$name]);
        }
    }

    /**
     * Clears all options
     */
    public function clearOptions()
    {
        $this->options = array();
    }

    /**
     * Setter for options
     *
     *
     * @return array
     */
    public function getCustomHeaders()
    {
        return $this->customHeaders;
    }

    /**
     * Setter for custom header
     *
     * @param string $header
     * @param string $value
     *
     */
    public function setCustomHeader($header, $value = null)
    {
        $headerPrefix = 'x-rosetteapi-';
        if (strlen($header) < strlen($headerPrefix) ||
            strcasecmp(substr($header, 0, strlen($headerPrefix)), $headerPrefix) != 0) {
            throw new RosetteException("Custom headers must start with \"$headerPrefix\"");
        }
        if (is_null($value) && array_key_exists($header, $this->customHeaders)) {
            unsset($this->customHeaders, $header);
        } else {
            $this->customHeaders[$header] = $value;
        }
    }

    /**
    * Adds custom headers to headers array if there are any
    *
    * @param array $headers
    *
    * @return array $headers
    **/
    private function addHeaders($headers)
    {
        foreach ($this->customHeaders as $key => $value) {
            $headers[$key] = $value;
        }

        return $headers;
    }

    /**
     * Clears all custom headers
     */
    public function clearCustomHeaders()
    {
        unset($this->customHeaders);
        $this->customHeaders = array();
    }

    public function getResponseHeader()
    {
        return $this->request->getResponseHeader();
    }

    /** Internal operations processor for most of the endpoints.
     *
     * @param $parameters
     * @param $subUrl
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    private function callEndpoint($parameters, $subUrl)
    {
        $this->subUrl = $subUrl;
        $resultObject = '';

        $this->headers = $this->addHeaders($this->headers);

        if (strlen($parameters->getMultiPartContent()) > 0) {
            $content = $parameters->getMultiPartContent();
            $filename = $parameters->getFileName();

            json_encode($content, JSON_FORCE_OBJECT);

            // create multipart
            $clrf = "\r\n";
            $multi = '';
            $boundary = md5(time());
            $multi .= '--' . $boundary . $clrf;
            $multi .= 'Content-Type: application/json' . "\r\n";
            $multi .= 'Content-Disposition: mixed; name="request"' . "\r\n" . "\r\n";
            $multi .= $parameters->serialize($this->options) . $clrf .$clrf;
            $multi .= '--' . $boundary . "\r\n";
            $multi .= 'Content-Type: text/plain' . "\r\n";
            $multi .= 'Content-Disposition: mixed; name="content"; filename="' . $filename . '"' . "\r\n" . "\r\n";
            $multi .= $content . "\r\n";
            $multi .= '--' . $boundary . '--';

            $this->headers['Content-Type'] = 'multipart/mixed';

            $url = $this->service_url . $this->subUrl;
            $resultObject = $this->postHttp($url, $this->headers, $multi);
        } else {
            $url = $this->service_url . $this->subUrl;
            $this->headers['Content-Type'] = 'application/json';
            $resultObject = $this->postHttp($url, $this->headers, $parameters->serialize($this->options));
        }
        return $resultObject;
    }

    /**
     * function makeRequest.
     *
     * Encapsulates the GET/POST
     *
     * @param $url
     * @param $headers
     * @param $data
     * @param $method
     *
     * @return string
     *
     * @throws RosetteException
     *
     */
    private function makeRequest($url, $headers, $data, $method)
    {
        if ($this->request->makeRequest($url, $headers, $data, $method, $this->url_params) === false) {
            throw new RosetteException($this->request->getResponseError);
        } else {
            $this->setResponseCode($this->request->getResponseCode());
            if ($this->getResponseCode() !== 200) {
                throw new RosetteException($this->request->getResponse()['message'], $this->getResponseCode());
            }
            return $this->request->getResponse();
        }
    }

    /**
     * Standard GET helper.
     *
     * @param $url
     * @param $headers
     * @param $options
     *
     * @return string : JSON string
     *
     * @throws RosetteException
     *
     * @internal param $url : target URL
     * @internal param $headers : header data
     */
    private function getHttp($url, $headers)
    {
        $method = 'GET';
        $response = $this->makeRequest($url, $headers, null, $method);

        return $response;
    }

    /**
     * Standard POST helper.
     *
     * @param $url
     * @param $headers
     * @param $data
     * @param $options
     *
     * @return string : JSON string
     *
     * @throws RosetteException
     *
     * @internal param $url : target URL
     * @internal param $headers : header data
     * @internal param $data : submission data
     */
    private function postHttp($url, $headers, $data)
    {
        $method = 'POST';
        $response = $this->makeRequest($url, $headers, $data, $method);

        return $response;
    }

    /**
     * Calls the Ping endpoint.
     *
     * @return mixed
     *
     * @throws \Rosette\Api\RosetteException
     */
    public function ping()
    {
        $url = $this->service_url . 'ping';
        $resultObject = $this->getHttp($url, $this->headers);

        return $resultObject;
    }

    /**
     * Calls the info endpoint.
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function info()
    {
        $url = $this->service_url . 'info';
        $resultObject = $this->getHttp($url, $this->headers);

        return $resultObject;
    }

    /**
     * Calls the language endpoint.
     *
     * @param $params
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function language($params)
    {
        return $this->callEndpoint($params, 'language');
    }

    /**
     * Calls the sentences endpoint.
     *
     * @param $params
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function sentences($params)
    {
        return $this->callEndpoint($params, 'sentences');
    }

    /**
     * Calls the tokens endpoint.
     *
     * @param $params
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function tokens($params)
    {
        return $this->callEndpoint($params, 'tokens');
    }

    /**
     * Calls the morphology endpoint.
     *
     * @param $params
     * @param null $facet
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function morphology($params, $facet = null)
    {
        if (!$facet) {
            $facet = RosetteConstants::$MorphologyOutput['COMPLETE'];
        }

        return $this->callEndpoint($params, 'morphology/' . $facet);
    }

    /**
     * Calls the entities endpoint.
     *
     * @param $params
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function entities($params)
    {
        return $this->callEndpoint($params, 'entities');
    }


    /**
     * Calls the categories endpoint.
     *
     * @param $params
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function categories($params)
    {
        return $this->callEndpoint($params, 'categories');
    }

    /**
     * Calls the sentiment endpoint.
     *
     * @param $params
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function sentiment($params)
    {
        return $this->callEndpoint($params, 'sentiment');
    }

    /**
     * Calls the name translation endpoint.
     *
     * @param $nameTranslationParams
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function nameTranslation($nameTranslationParams)
    {
        return $this->callEndpoint($nameTranslationParams, 'name-translation');
    }

    /**
     * Calls the name similarity endpoint.
     *
     * @param $nameSimilarityParams
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function nameSimilarity($nameSimilarityParams)
    {
        return $this->callEndpoint($nameSimilarityParams, 'name-similarity');
    }

    /**
     * Calls the name deduplication endpoint.
     *
     * @param $nameDeduplicationParams
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function nameDeduplication($nameDeduplicationParams)
    {
        return $this->callEndpoint($nameDeduplicationParams, 'name-deduplication');
    }

    /**
     * Calls the relationships endpoint.
     *
     * @param $params
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function relationships($params)
    {
        return $this->callEndpoint($params, 'relationships');
    }

    /**
     * Calls the text-embedding endpoint.
     *
     * Deprecated.  Please use `semanticVectors` instead
     *
     * @param $params
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function textEmbedding($params)
    {
        return $this->callEndpoint($params, 'text-embedding');
    }

    /**
     * Calls the semantic vectors endpoint.
     *
     * @param $params
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function semanticVectors($params)
    {
        return $this->callEndpoint($params, 'semantics/vector');
    }

    /**
    * Calls the syntax/dependencies endpoint
    *
    * @param $params
    *
    * @return mixed
    *
    * @throws RosetteException
    */
    public function syntaxDependencies($params)
    {
        return $this->callEndpoint($params, 'syntax/dependencies');
    }

    /**
    * Calls the transliteration endpoint
    *
    * @param $params
    *
    * @return mixed
    *
    * @throws RosetteException
    */
    public function transliteration($params)
    {
        return $this->callEndpoint($params, 'transliteration');
    }

    /**
    * Calls the topics endpoint
    *
    * @param $params
    *
    * @return mixed
    *
    * @throws RosetteException
    */
    public function topics($params)
    {
        return $this->callEndpoint($params, 'topics');
    }

    /**
    * Calls the similarTerms endpoint
    *
    * @param $params
    *
    * @return mixed
    *
    * @throws RosetteException
    */
    public function similarTerms($params)
    {
        return $this->callEndpoint($params, 'semantics/similar');
    }

    /**
    * Calls the addressSimilarity endpoint
    *
    * @param $params
    *
    * @return mixed
    *
    * @throws RosetteException
    */
    public function addressSimilarity($params)
    {
        return $this->callEndpoint($params, 'address-similarity');
    }
}
