<?php

/**
 * class NameDeduplicationParameters.
 *
 * Parameters that are necessary for name deduplication operations.
 *
 * @copyright 2017-2018 Basis Technology Corporation.
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
 * Class NameDeduplicationParameters.
 */
class NameDeduplicationParameters extends RosetteParamsSetBase
{
    /**
     * @var array list of Name objects
     */
    public $names;
    /**
     * @var float threshold 0 - 1 range for cluster size. Can be null to use default.
     */
    public $threshold;
    /**
     * constructor.
     *
     * @param array - list of Name objects
     * @param float - threshold value used for cluster sizing
     */
    public function __construct($names, $threshold = null)
    {
        $this->names = $names;
        $this->threshold = $threshold;
    }

    /**
     * Validates parameters.
     *
     * @throws RosetteException
     */
    public function validate()
    {
        if (empty($this->names)) {
            throw new RosetteException(
                sprintf('Required name deduplication parameter not supplied: names'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
        if ($this->threshold != null && ($this->threshold <= 0 || $this->threshold >= 1.0)) {
            throw new RosetteException(
                sprintf("Threshold must be in the range of 0 to 1.0"),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
    }
}
