<?php

/**
 * Address represents an unfielded address in Rosette API.
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
 * Class that represents an Address.
 */
class UnfieldedAddress extends RosetteParamsSetBase implements IAddress
{
    /**
     * The address
     *
     * @var string
     */
    public $address;

    /**
     * Constructor.
     *
     * @param $address
     */
    public function __construct(
        $address = null
    )
    {
        if ($address === null)
        {
            throw new RosetteException(
                sprintf('Address cannot be null'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
        $this->address = $address;
    }

    /**
     * required validate function
     */
    public function validate()
    {
        // nothing to validate
    }

    /**
     * is this a fielded address?
     */
    public function fielded()
    {
        return false;
    }
}
