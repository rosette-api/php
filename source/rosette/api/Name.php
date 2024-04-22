<?php

/**
 * Name represents an entity name in Rosette API.
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
 * Class that represents a Name.
 */
class Name extends RosetteParamsSetBase
{
    /**
     * Textual form of the name.
     *
     * @var string
     */
    public $text;
    /**
     * Entity type of the name.
     *
     * @var string
     */
    public $entityType;
    /**
     * Language of the name.
     *
     * @var string
     */
    public $language;

    /**
     * Script in which the name is written.
     *
     * @var string
     */
    public $script;

    /**
     * Constructor.
     *
     * @param $text
     * @param $entityType
     * @param $language
     * @param $script
     *
     * @throws RosetteException
     */
    public function __construct($text, $entityType = null, $language = null, $script = null)
    {
        if ($text === null) {
            throw new RosetteException(
                sprintf('The text of a name is required'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
        $this->text = $text;
        $this->entityType = $entityType;
        $this->language = $language;
        $this->script = $script;
    }

    /**
     * required validate function
     */
    public function validate()
    {
        // nothing to validate
    }
}
