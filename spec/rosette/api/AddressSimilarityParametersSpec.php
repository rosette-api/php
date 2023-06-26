<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use rosette\api\RosetteConstants;

class AddressSimilarityParametersSpec extends ObjectBehavior
{
    public function it_passes_validation(\rosette\api\IAddress $address1, \rosette\api\IAddress $address2)
    {
        $this->beConstructedWith($address1, $address2);
        $this->shouldNotThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_has_address1_undefined(\rosette\api\IAddress $address1, \rosette\api\IAddress $address2)
    {
        $this->beConstructedWith($address1, $address2);
        $this->address1 = null;
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_has_address2_undefined(\rosette\api\IAddress $address1, \rosette\api\IAddress $address2)
    {
        $this->beConstructedWith($address1, $address2);
        $this->address2 = null;
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }
}
