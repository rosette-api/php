<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddressSpec extends ObjectBehavior
{
    public function it_should_except_with_no_address()
    {
        $this->beConstructedWith(null);
        $this->shouldThrow('rosette\api\RosetteException')->duringInstantiation();
    }

    public function it_validates_arguments()
    {
        $house = 'house';
        $houseNumber = 'houseNumber';
        $road = 'road';
        $unit = 'unit';
        $level = 'level';
        $staircase = 'staircase';
        $entrance = 'entrance';
        $suburb = 'suburb';
        $cityDistrict = 'cityDistrict';
        $city = 'city';
        $island = 'island';
        $stateDistrict = 'stateDistrict';
        $state = 'state';
        $countryRegion = 'countryRegion';
        $country = 'country';
        $worldRegion = 'worldRegion';
        $postCode = 'postCode';
        $poBox = 'poBox';

        $this->beConstructedWith(
            $house,
            $houseNumber,
            $road,
            $unit,
            $level,
            $staircase,
            $entrance,
            $suburb,
            $cityDistrict,
            $city,
            $island,
            $stateDistrict,
            $state,
            $countryRegion,
            $country,
            $worldRegion,
            $postCode,
            $poBox
        );

        $this->house->shouldBeLike($house);
        $this->houseNumber->shouldBeLike($houseNumber);
        $this->road->shouldBeLike($road);
        $this->unit->shouldBeLike($unit);
        $this->level->shouldBeLike($level);
        $this->staircase->shouldBeLike($staircase);
        $this->entrance->shouldBeLike($entrance);
        $this->suburb->shouldBeLike($suburb);
        $this->cityDistrict->shouldBeLike($cityDistrict);
        $this->city->shouldBeLike($city);
        $this->island->shouldBeLike($island);
        $this->stateDistrict->shouldBeLike($stateDistrict);
        $this->state->shouldBeLike($state);
        $this->countryRegion->shouldBeLike($countryRegion);
        $this->country->shouldBeLike($country);
        $this->worldRegion->shouldBeLike($worldRegion);
        $this->postCode->shouldBeLike($postCode);
        $this->poBox->shouldBeLike($poBox);
    }
}
