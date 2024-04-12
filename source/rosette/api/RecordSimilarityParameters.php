<?php

/**
 * class NameSimilarityParameters.
 *
 * Parameters that are necessary for name similarity operations.
 *
 * @copyright 2024 Basis Technology Corporation.
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
* Class RecordSimilarityParameters.
*/
class RecordSimilarityParameters extends RosetteParamsSetBase
{
    /**
    * @var array
    */
    public $fields;

    /**
    * @var array
    */
    public $properties;

    /**
    * @var array
    */
    public $records;

    /**
     * constructor
     *
     * @param array $fields - the fields of the records to compare
     * @param array $properties - the properties of the comparison
     * @param array $records - the records to compare
     */
    public function __construct($fields, $properties, $records)
    {
        $this->fields = $fields;
        $this->properties = $properties;
        $this->records = $records;
    }



    /**
     * Validates parameters.
     *
     * @throws RosetteException
     */
    public function validate()
    {
        if (empty($this->fields)) {
            throw new RosetteException(
                sprintf('Required record similarity parameter not supplied: fields'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
        if (empty($this->properties)) {
            throw new RosetteException(
                sprintf('Required record similarity parameter not supplied: properties'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
        if (empty($this->records)) {
            throw new RosetteException(
                sprintf('Required record similarity parameter not supplied: records'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
    }
}