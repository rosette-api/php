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
     * API URL
     *
     * @var string
     */
    private $url;
    /**
     * Request data
     *
     * @var string
     */
    private $data;
    /**
     * Request headers
     *
     * @var string
     */
    private $headers;
    /**
     * POST or GET
     *
     * @var string
     */
    private $method;
    /**
     * PHP curl handle
     *
     * @var mixed
     */
    private $curl_handle;
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
        $this->initialized = false;
    }

    /**
     * class destructor
     */
    public function __destruct()
    {
        if ($this->initialized) {
            curl_close($this->curl_handle);
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
    public function makeRequest($url, $headers, $data, $method)
    {
        if ($this->initialized === true) {
            curl_reset($this->curl_handle);
        } else {
            $this->curl_handle = curl_init();
            $this->initialized = true;
        }

        curl_setopt($this->curl_handle, CURLOPT_URL, $url);
        curl_setopt($this->curl_handle, CURLOPT_HTTPHEADER, $headers);

        if ($method === 'POST') {
            curl_setopt($this->curl_handle, CURLOPT_POST, true);
            curl_setopt($this->curl_handle, CURLOPT_POSTFIELDS, $data);
        } elseif ($method === 'GET') {
            curl_setopt($this->curl_handle, CURLOPT_HTTPGET, true);
        }

        curl_setopt($this->curl_handle, CURLOPT_HEADER, 1);
        curl_setopt($this->curl_handle, CURLOPT_RETURNTRANSFER, true);
        
        $this->response = curl_exec($this->curl_handle);

        return $this->response !== false;
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
        if ($this->curl_handle === null) {
            return 0;
        } else {
            return curl_getinfo($this->curl_handle, CURLINFO_HTTP_CODE);
        }
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
        return curl_error($this->curl_handle);
    }

    /**
     * Returns the header from the curl response
     *
     * @param $curl_handle
     * @param $response
     *
     * @return array
     */
    private function getResponseHeaders()
    {
        $header_size = $this->curlHeaderSize($this->curl_handle);

        return explode(PHP_EOL, substr($this->response, 0, $header_size));
    }

    /**
     * Returns the body element of the response object
     *
     * @return string
     */
    private function getResponseBody()
    {
        $body = '';
        $header_size = $this->curlHeaderSize($this->curl_handle);
        if (!empty($this->response)) {
            $body = substr($this->response, $header_size);
            if ($this->checkForZip($body)) {
                $body = gzinflate(substr($body, 10, -8));
            }
        }

        return $body;
    }

    /**
     * Returns the associative array response or the error message
     *
     * @return mixed
     */
    public function getResponse()
    {
        if ($this->response !== false) {
            $response = [ 'headers' => $this->headersToArray() ];
            $responseBody = $this->getResponseBody();
            if (empty($responseBody)) {
                $response = array_merge($response, [ 'body' => 'empty' ]);
            } else {
                $response = array_merge($response, json_decode($this->getResponseBody(), true));
            }
        } else {
            $response = $this->getResponseError();
        }

        return $response;
    }


    /**
     * function headersToArray
     *
     * Converts the http response header string to an associative array
     *
     * @param $headers
     *
     * @returns associative array of headers
     */
    private function headersToArray()
    {
        $headers = $this->getResponseHeaders();
        $array_headers = array();
        foreach ($headers as $k=>$v) {
            $t = explode(':', $v, 2);
            if (isset($t[1])) {
                $array_headers[ trim($t[0]) ] = trim($t[1]);
            } else {
                if (strlen(trim($v)) > 0) {
                    $array_headers[] = $v;
                }
                if (preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out)) {
                    $array_headers['response_code'] = intval($out[1]);
                }
            }
        }
        return $array_headers;
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
    /**
     * Returns the curl header size
     *
     * @param $curl_handle
     *
     * @return int
     */
    private function curlHeaderSize($curl_handle)
    {
        if ($curl_handle === null) {
            return 0;
        } else {
            return curl_getinfo($curl_handle, CURLINFO_HEADER_SIZE);
        }
    }
}
