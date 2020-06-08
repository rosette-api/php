<?php

/**
 * Address represents a fielded address in Rosette API.
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
 * Class that represents an FieldedAddress.
 */
class FieldedAddress extends RosetteParamsSetBase implements IAddress
{
    /**
     * The address house.
     *
     * @var string
     */
    public $house;

    /**
     * The address house number.
     *
     * @var string
     */
    public $houseNumber;

    /**
     * The address road.
     *
     * @var string
     */
    public $road;

    /**
     * The address unit.
     *
     * @var string
     */
    public $unit;

    /**
     * The address level.
     *
     * @var string
     */
    public $level;

    /**
     * The address staircase.
     *
     * @var string
     */
    public $staircase;

    /**
     * The address entrance.
     *
     * @var string
     */
    public $entrance;

    /**
     * The address suburb.
     *
     * @var string
     */
    public $suburb;

    /**
     * The address city district.
     *
     * @var string
     */
    public $cityDistrict;

    /**
     * The address city.
     *
     * @var string
     */
    public $city;

    /**
     * The address island.
     *
     * @var string
     */
    public $island;

    /**
     * The address state district.
     *
     * @var string
     */
    public $stateDistrict;

    /**
     * The address state.
     *
     * @var string
     */
    public $state;

    /**
     * The address country region.
     *
     * @var string
     */
    public $countryRegion;

    /**
     * The address country.
     *
     * @var string
     */
    public $country;

    /**
     * The address world region.
     *
     * @var string
     */
    public $worldRegion;

    /**
     * The address post code.
     *
     * @var string
     */
    public $postCode;

    /**
     * The address P.O. Box.
     *
     * @var string
     */
    public $poBox;

    /**
     * Constructor.
     *
     * @param $house
     * @param $houseNumber
     * @param $road
     * @param $unit
     * @param $level
     * @param $staircase
     * @param $entrance
     * @param $suburb
     * @param $cityDistrict
     * @param $city
     * @param $island
     * @param $stateDistrict
     * @param $state
     * @param $countryRegion
     * @param $country
     * @param $worldRegion
     * @param $postCode
     * @param $poBox
     */
    public function __construct(
        $house = null,
        $houseNumber = null,
        $road = null,
        $unit = null,
        $level = null,
        $staircase = null,
        $entrance = null,
        $suburb = null,
        $cityDistrict = null,
        $city = null,
        $island = null,
        $stateDistrict = null,
        $state = null,
        $countryRegion = null,
        $country = null,
        $worldRegion = null,
        $postCode = null,
        $poBox = null
    )
    {
        if ($house === null &&
            $houseNumber === null &&
            $road === null &&
            $unit === null &&
            $level === null &&
            $staircase === null &&
            $entrance === null &&
            $suburb === null &&
            $cityDistrict === null &&
            $city === null &&
            $island === null &&
            $stateDistrict === null &&
            $state === null &&
            $countryRegion === null &&
            $country === null &&
            $worldRegion === null &&
            $postCode === null &&
            $poBox === null)
        {
            throw new RosetteException(
                sprintf('At least one address field is required'),
                RosetteException::$BAD_REQUEST_FORMAT
            );
        }
        $this->house = $house;
        $this->houseNumber = $houseNumber;
        $this->road = $road;
        $this->unit = $unit;
        $this->level = $level;
        $this->staircase = $staircase;
        $this->entrance = $entrance;
        $this->suburb = $suburb;
        $this->cityDistrict = $cityDistrict;
        $this->city = $city;
        $this->island = $island;
        $this->stateDistrict = $stateDistrict;
        $this->state = $state;
        $this->countryRegion = $countryRegion;
        $this->country = $country;
        $this->worldRegion = $worldRegion;
        $this->postCode = $postCode;
        $this->poBox = $poBox;
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
        return true;
    }
}
