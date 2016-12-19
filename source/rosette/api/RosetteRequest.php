<?php

/**
 * class RosetteRequest.
 *
 * Wrapper class for php Curl
 *
 * @copyright 2014-2016 Basis Technology Corporation.
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

/**
 * Class RosetteRequest.
 *
 * Wraps the php curl commands into an easier to use (and mock) object
 */
class RosetteRequest
{

    /**
     * Response object
     *
     * @var mixed
     */
    private $response;
    /**
     * indicates that curl is initialized
     *
     * @var bool
     */
    private $initialized;

    /**
     * class constructor
     *
     */
    public function __construct()
    {
        $this->initialized = true;
    }

    /**
     * class destructor
     */
    public function __destruct()
    {
        if ($this->initialized) {
            $this->initialized = false;
        }
    }

    /**
     * Wraps the curl call
     *
     * @param string $url URL of the Api API
     * @param string $data Response data
     * @param string $headers Response headers
     * @param string $method GET or POST
     *
     * @return bool
     */
    public function makeRequest($url, $headers, $data, $method, $url_params = null)
    {
        // Unfortunately, the 'options' argument for post and get is NOT for
        // query parameters (as it is in Python). Hence, the construction.
        if (!is_null($url_params)) {
            $url = $url . '?' . http_build_query($url_params);
        }
        try {
            if ($method === 'POST') {
                $this->response = \Requests::post($url, $headers, $data);
            } elseif ($method === 'GET') {
                $this->response = \Requests::get($url, $headers);
            }
            return true;
        } catch (Requests_Exception $e) {
            throw new RosetteException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Returns curl response code
     *
     * @param $curl_handle
     *
     * @return int
     */
    public function getResponseCode()
    {
        return !$this->response ? 0 : $this->response->status_code;
    }


    /**
     * Returns the error string if curl error
     *
     * @param $curl_handle
     *
     * @return string
     */
    public function getResponseError()
    {
        return $this->response->body;
    }

    /**
     * Returns the header from the curl response
     *
     * @param $curl_handle
     * @param $response
     *
     * @return array
     */
    public function getResponseHeader()
    {
        return $this->response->headers;
    }

    /**
     * Returns the body element of the response object
     *
     * @return string
     */
    public function getResponseBody()
    {
        return $this->response->body;
    }

    /**
     * Returns the associative array response or the error message
     *
     * @return mixed
     */
    public function getResponse()
    {
        if (!$this->response) {
            return array();
        }
        $headers['response_code'] = $this->response->status_code;
        foreach ($this->response->headers as $key => $value) {
            $headers[$key] = $value;
        }
        $response['headers'] = $headers;
        return array_merge($response, json_decode($this->response->body, true));
    }

    /**
     * Checks for the signature values of gzip and bzip2
     *
     * @param $content
     *
     * @return bool
     */
    private function checkForZip($content)
    {
        $result = false;

        if (strlen($content) > 3) {
            if (bin2hex(substr($content, 0, 2)) == '1f8b') {
                $result = true;
            } elseif (substr($content, 0, 3) == 'BZh') {
                $result = true;
            }
        }

        return $result;
    }
}
