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
    private static $binding_version = '1.1';

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
     * True if the version has already been checked.  Saves round trips.
     *
     * @var bool
     */
    private $version_checked;

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
                          "User-Agent: RosetteAPIPHP/" . self::$binding_version, );

        $this->setServiceUrl($service_url);
        $this->setDebug(false);
        $this->setTimeout(300);
        $this->version_checked = false;
        $this->subUrl = null;
        $this->max_retries = 5;
        $this->ms_between_retries = 500000;
        $this->mock_request = null;
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
     * @param bool $debug
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
        $this->checkVersion($this->service_url);
        $this->subUrl = $subUrl;
        $resultObject = '';

        if (strlen($parameters->getMultiPartContent()) > 0) {
            $content = $parameters->getMultiPartContent();
            $filename = $parameters->fileName;

            json_encode($content, JSON_FORCE_OBJECT);

            // create multipart
            $clrf = "\r\n";
            $multi = '';
            $boundary = md5(time());
            $multi .= '--' . $boundary . $clrf;
            $multi .= 'Content-Type: application/json' . "\r\n";
            $multi .= 'Content-Disposition: mixed; name="request"' . "\r\n" . "\r\n";
            $multi .= $parameters->serialize(false) . $clrf .$clrf;
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
            $resultObject = $this->postHttp($url, $this->headers, $parameters->serialize());
        }
        return $resultObject;
    }

    /**
     * Checks the server version against the api (or provided )version.
     *
     * @param $url
     * @param $versionToCheck
     *
     * @return bool
     *
     * @throws RosetteException
     */
    private function checkVersion($url, $versionToCheck = null)
    {
        if (!$this->version_checked) {
            if (!$versionToCheck) {
                $versionToCheck = self::$binding_version;
            }
            $resultObject = $this->postHttp($url . "info?clientVersion=$versionToCheck", $this->headers, null);

            // should not get called due to makeRequest checks, but just in case, we want to 
            // avoid an incompatible version error when it's something else.
            if ($this->getResponseCode() !== 200) {
                throw new RosetteException($resultObject['message'], $this->getResponseCode());
            }

            if (array_key_exists('versionChecked', $resultObject) && $resultObject['versionChecked'] === true) {
                $this->version_checked = true;
            } else {
                throw new RosetteException(
                    'The server version is not compatible with binding version ' . strval($versionToCheck),
                    RosetteException::$INCOMPATIBLE_VERSION
                );
            }
        }

        return $this->version_checked;
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
     * @param $resolve_entities
     *
     * @return mixed
     *
     * @throws RosetteException
     */
    public function entities($params, $resolve_entities = false)
    {
        return $resolve_entities ? $this->callEndpoint($params, 'entities/linked') : $this->callEndpoint($params, 'entities');
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
