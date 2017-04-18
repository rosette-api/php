<?php

/**
 * class TransliterationParameters.
 *
 * Parameters that are necessary for name translation operations.
 *
 * @copyright 2016-2017 Basis Technology Corporation.
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
 * Class TransliterationParameters.
 */
class TransliterationParameters extends RosetteParamsSetBase
{
    /**
     * @var string content to translate
     */
    public $content;
    /**
     * @var string targetLanguage ISO 639-3 code for the translation language
     */
    public $targetLanguage;
    /**
     * @var string targetScript ISO 15924 code for script
     */
    public $targetScript;
    /**
     * @var string sourceLanguage ISO 693-3 code for language of origin
     */
    public $sourceLanguage;
    /**
     * @var string sourceScript ISO 15924 code for content
     */
    public $sourceScript;
    /**
     * constructor.
     */
    public function __construct($content, $targetLanguage, $targetScript, $sourceLanguage, $sourceScript)
    {
        $this->content = $content;
        $this->targetLanguage = $targetLanguage;
        $this->targetScript = $targetScript;
        $this->sourceLanguage = $sourceLanguage;
        $this->sourceScript = $sourceScript;
    }

    /**
     * Validates parameters.
     *
     * @throws RosetteException
     */
    public function validate()
    {
        if (empty($this->content)) {
            throw new RosetteException(
                sprintf('Required transliteration parameter not supplied: content'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
        if (empty($this->targetLanguage)) {
            throw new RosetteException(
                sprintf('Required transliteration parameter not supplied: targetLanguage'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
        if (empty($this->targetScript)) {
            throw new RosetteException(
                sprintf('Required transliteration parameter not supplied: targetScript'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
        if (empty($this->sourceLanguage)) {
            throw new RosetteException(
                sprintf('Required transliteration parameter not supplied: sourceLanguage'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
        if (empty($this->sourceScript)) {
            throw new RosetteException(
                sprintf('Required transliteration parameter not supplied: sourceScript'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
    }
}
