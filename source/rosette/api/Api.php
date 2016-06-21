<?php

/**
 * Api.
 *
 * Primary class for interfacing with the Rosette API
 *
 * @copyright 2015-2016 Basis Technology Corporation.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * @license http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 **/
namespace rosette\api;

// autoload classes in the package
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__DIR__));
spl_autoload_register(function ($class) {
    $class = preg_replace('/.+\\\\/', '', $class);
    require_once $class . '.php';
});

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
    private static $binding_version = '1.1.2';

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
     * Endpoint for the operation.
     *
     * @var null|string
     */
    private $subUrl;

    /**
     * response code
     *
     * @var
     */
    private $response_code;

    /**
     * max retries
     *
     * @var int
     */
    private $max_retries;

    /**
     * retry sleep count (ms)
     *
     * @var int
     */
    private $ms_between_retries;

    /**
     * request override - for testing
     *
     * @RosetteRequest
     */
    private $mock_request;

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
     * Create an L{API} object.
     *
     * @param string $service_url URL of the Api API
     * @param string $user_key    An authentication string to be sent as user_key with
     *                            all requests.
     */
    public function __construct($user_key, $service_url  = 'https://api.rosette.com/rest/v1/')
    {
        $this->user_key = $user_key;



        $this->headers = array("X-RosetteAPI-Key: $user_key",
                          "Content-Type: application/json",
                          "Accept: application/json",
                          "Accept-Encoding: gzip",
                          "User-Agent: RosetteAPIPHP/" . self::$binding_version,
                          "X-RosetteAPI-Binding: php",
                          "X-RosetteAPI-Binding-Version: " . self::$binding_version );

        $this->setServiceUrl($service_url);
        $this->setDebug(false);
        $this->setTimeout(300);
        $this->subUrl = null;
        $this->max_retries = 5;
        $this->ms_between_retries = 500000;
        $this->mock_request = null;
        $this->options = array();
    }
    
    /**
     * Sets on override Request for mocking purposes
     *
     * @param $requestObject
     */
    public function setMockRequest($requestObject)
    {
        $this->mock_request = $requestObject;
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
     * Returns the max timeout value (seconds).
     *
     * @return mixed
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Sets the max timeout value (seconds).
     *
     * @param mixed $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Enables debug (more verbose output).
     *
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        $debug_header = 'X-RosetteAPI-Devel: true';
        $index = array_search($debug_header, $this->headers, true);
        if ($index === false) {
            if ($debug === true) {
                $this->headers[] = $debug_header;
            }
        } else {
            if ($debug === false) {
                unset($this->headers[$index]);
            }
        }
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
     * Getter for options
     *
     * @param string $name
     *
     * @return string
     */
    public function getOption($name)
    {
        if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        } else {
            return null;
        }
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
        if ($value != null) {
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
     * Setter for custom headers
     *
     * @param array $headers
     *
     */
    public function setCustomHeaders($header)
    {
        $this->clearCustomHeaders();
        if ($header != null) {
            if (preg_match("/^X-RosetteAPI-/", $header)) {
                array_push($this->customHeaders, $header);
            } else {
                throw new RosetteException("Custom headers must start with \"X-\"");
            }
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
        $customHeaders = $this->getCustomHeaders();

        if (sizeof($customHeaders) > 0) {
            foreach ($customHeaders as $value) {
                array_push($headers, $value);
            }
            return $headers;
        } else {
            return $headers;
        }
    }

    /**
     * Clears all custom headers
     */
    public function clearCustomHeaders()
    {
        $this->customHeaders = array();
    }


    /**
     * Replaces a header item with a new one
     */
    private function replaceHeaderItem($old_header_item, $new_header_item)
    {
        $index = array_search($old_header_item, $this->headers, true);
        if ($index !== false) {
            unset($this->headers[$index]);
        }
        if (strlen(trim($new_header_item)) > 0) {
            $this->headers[] = $new_header_item;
        }
    }

    /**
     * Internal operations processor for most of the endpoints.
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

            $this->replaceHeaderItem('Content-Type: application/json', 'Content-Type: multipart/mixed');

            $url = $this->service_url . $this->subUrl;

            $resultObject = $this->postHttp($url, $this->headers, $multi);
        } else {
            $url = $this->service_url . $this->subUrl;
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
        $request = $this->mock_request != null ? $this->mock_request : new RosetteRequest();
        for ($retries = 0; $retries < $this->max_retries; $retries++) {
            if ($request->makeRequest($url, $headers, $data, $method) === false) {
                throw new RosetteException($request->getResponseError);
            } else {
                $this->setResponseCode($request->getResponseCode());
                if ($this->getResponseCode() === 429) {
                    usleep($this->ms_between_retries);
                    continue;
                } elseif ($this->getResponseCode() !== 200) {
                    throw new RosetteException($request->getResponse()['message'], $this->getResponseCode());
                }
                return $request->getResponse();
            }
        }
        if ($this->getResponseCode() !== 200) {
            throw new RosetteException($request->getResponse()['message'], $this->getResponseCode());
        } else {
            return $request->getResponse();
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
}
