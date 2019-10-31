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
     * @var string content is the text to be analyzed (required if no contentUri)
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
     * @var string multiPartContent contains content for multipart packaging.  Private to prevent
     * processing by the serializer
     */
    private $multiPartContent;

    /**
     * @var string fileName is the name of the file containing content to be analyzed
     */
    private $fileName;

    /**
     * @var string genre to categorize the input data
     */
    public $genre;

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
        $this->multiPartContent = '';
        $this->genre = '';
    }

    /**
     * Setter for multiPartContent. Clears the content and contentUri properties if it contains
     * data
     *
     * @param $str_content
     */
    public function setMultiPartContent($str_content)
    {
        $this->multiPartContent = trim($str_content);
        if (strlen($str_content) > 0) {
            $this->content = '';
            $this->contentUri = '';
        }
    }

    /**
     * Getter for multiPartContent
     *
     * @return string
     */
    public function getMultiPartContent()
    {
        return $this->multiPartContent;
    }

    /**
     * Getter for fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Validates parameters.
     *
     * @throws RosetteException
     */
    public function validate()
    {
        if (empty(trim($this->multiPartContent))) {
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
    }

    /**
     * Loads a file into the object.
     *
     * The file will be read as bytes; the appropriate conversion will be determined by the server.
     *
     * @param $path : Pathname of a file acceptable to the C{open}
     * function.
     *
     * @throws RosetteException
     */
    public function loadDocumentFile($path)
    {
        $this->loadDocumentString(file_get_contents($path), true);
        $this->fileName = $path;
    }

    /**
     * Loads a string into the object.
     *
     * The string will be taken as bytes or as Unicode dependent upon its native type and the data type asked for;
     * if the type is HTML or XHTML, bytes are expected, the encoding to be determined by the server.
     *
     * @param $stringData
     * @param $multiPart
     *
     * @throws RosetteException
     */
    public function loadDocumentString($stringData, $multiPart = false)
    {
        if ($multiPart === true) {
            $this->setMultiPartContent($stringData);
        } else {
            $this->content = $stringData;
            $this->multiPartContent = '';
        }
    }
}
