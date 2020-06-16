<?php

/**
 * class AddressSimilarityParameters.
 *
 * Parameters that are necessary for address similarity operations.
 *
 * @copyright 2020 Basis Technology Corporation.
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
 * Class AddressSimilarityParameters.
 */
class AddressSimilarityParameters extends RosetteParamsSetBase
{
    /**
     * @var IAddress Address1 address 1
     */
    public $address1;
    /**
     * @var IAddress Address2 address 2
     */
    public $address2;
    /**
     * constructor.
     *
     * @param IAddress - Address1 to be compared
     * @param IAddress - Address2 to be compared
     */
    public function __construct(IAddress $Address1, IAddress $Address2)
    {
        $this->address1 = $Address1;
        $this->address2 = $Address2;
    }

    /**
     * Validates parameters.
     *
     * @throws RosetteException
     */
    public function validate()
    {
        if (empty($this->address1)) {
            throw new RosetteException(
                sprintf('Required address similarity parameter not supplied: Address1'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
        if (empty($this->address2)) {
            throw new RosetteException(
                sprintf('Required address similarity parameter not supplied: Address2'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
    }
}
