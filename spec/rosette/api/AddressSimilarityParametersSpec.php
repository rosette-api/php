<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddressSimilarityParametersSpec extends ObjectBehavior
{
    public function it_passes_validation(\rosette\api\IAddress $address1, \rosette\api\IAddress $address2)
    {
        $this->beConstructedWith($address1, $address2);
        $this->shouldNotThrow('rosette\api\RosetteException')->duringValidate();
    }

    public function it_has_address1_undefined(\rosette\api\IAddress $address1, \rosette\api\IAddress $address2)
    {
        $this->beConstructedWith($address1, $address2);
        $this->address1 = null;
        $this->shouldThrow('rosette\api\RosetteException')->duringValidate();
    }

    public function it_has_address2_undefined(\rosette\api\IAddress $address1, \rosette\api\IAddress $address2)
    {
        $this->beConstructedWith($address1, $address2);
        $this->address2 = null;
        $this->shouldThrow('rosette\api\RosetteException')->duringValidate();
    }
}
