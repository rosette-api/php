<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use rosette\api\RosetteConstants;

class NameSimilarityParametersSpec extends ObjectBehavior
{
    public function it_passes_validation(\rosette\api\Name $name1, \rosette\api\Name $name2)
    {
        $this->beConstructedWith($name1, $name2);
        $this->shouldNotThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_has_name1_undefined(\rosette\api\Name $name1, \rosette\api\Name $name2)
    {
        $this->beConstructedWith($name1, $name2);
        $this->name1 = null;
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_has_name2_undefined(\rosette\api\Name $name1, \rosette\api\Name $name2)
    {
        $this->beConstructedWith($name1, $name2);
        $this->name2 = null;
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }
}
