<?php

/**
 * Address represents an address in Analytics API.
 *
 * @copyright 2020-2024 Basis Technology Corporation.
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
 * Interface implemented by all Addresses
 */
interface IAddress
{
    /**
     * Returns whether or not this address is fielded.
     *
     * @return boolean
     */
    public function fielded();              // Is this a fielded address?
}
