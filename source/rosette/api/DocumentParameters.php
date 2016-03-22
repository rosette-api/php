<?php

/**
 * class DocumentParameters.
 *
 * Parameter class for the standard Rosette API endpoints.  Does not include Name Translation
 *
 * @copyright 2014-2015 Basis Technology Corporation.
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
 * Class DocumentParameters.
 */
class DocumentParameters extends RosetteParamsSetBase
{
    /**
     * @var string ontent is the text to be analyzed (required if no contentUri)
     */
    public $content;

    /**
     * @var string contentUri is a URL to content that will be analyzed (required if no content)
     */
    public $contentUri;

    /**
     * @var string language is the language of the content (optional)
     */
    public $language;

    /**
     * @var bool useMultiPart determines if the user wishes to upload a multipart
     */
    public $useMultiPart;

    /**
     * @var string fileName is the name of the file containing content to be analyzed
     */
    public $fileName;

    /**
     * Constructor.
     *
     * @throws RosetteException
     */
    public function __construct()
    {
        $this->content = '';
        $this->contentUri = '';
        $this->language = '';
    }

    /**
     * Validates parameters.
     *
     * @throws RosetteException
     */
    public function validate()
    {
        if (empty(trim($this->content))) {
            if (empty(trim($this->contentUri))) {
                throw new RosetteException(
                    'Must supply one of Content or ContentUri',
                    RosetteException::$INVALID_DATATYPE
                );
            }
        } else {
            if (!empty(trim($this->contentUri))) {
                throw new RosetteException(
                    'Cannot supply both Content and ContentUri',
                    RosetteException::$INVALID_DATATYPE
                );
            }
        }
    }

    /**
     * Loads a file into the object.
     *
     * The file will be read as bytes; the appropriate conversion will be determined by the server.
     *
     * @param $path : Pathname of a file acceptable to the C{open}
     * function.
     * @param null $dataType
     *
     * @throws RosetteException
     */
    public function loadDocumentFile($path, $dataType = null)
    {
        if (!$dataType) {
            $dataType = RosetteConstants::$DataFormat['UNSPECIFIED'];
        }
        $this->loadDocumentString(file_get_contents($path), $dataType);
        $this->useMultiPart = true;
        $this->fileName = $path;
        /*$this->loadDocumentString(file_get_contents($path));
        $headers = array('X-RosetteAPI-Key' => $this->user_key,
                          'Content-Type' => 'multipart/form-data',
                          'Accept' => '/*',
                          'Accept-Encoding' => 'gzip',
                          'User-Agent' => 'RosetteAPIPHP/0.8' );
        $post = array('filedata' =>file_get_contents($path), 'filename' => realpath($path));
        //$post['file'] = new \CurlFile(realpath($path), 'multipart/form-data', realpath($path));

           $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://10.0.0.127:8181/rest/v1/sentiment');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            //curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $resCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($response === false){
                echo curl_errno($ch);
                echo curl_error($ch);
            }
            curl_close($ch);
            $response = explode(PHP_EOL, $response);
            var_dump($response);*/
    }

    /**
     * Loads a string into the object.
     *
     * The string will be taken as bytes or as Unicode dependent upon its native type and the data type asked for;
     * if the type is HTML or XHTML, bytes are expected, the encoding to be determined by the server.
     *
     * @param $stringData
     * @param $dataType
     *
     * @throws RosetteException
     */
    public function loadDocumentString($stringData)
    {
        $this->content = $stringData;
    }
}
