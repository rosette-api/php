<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use rosette\api\RosetteConstants;

class NameDeduplicationParametersSpec extends ObjectBehavior
{
    public function it_passes_validation(\rosette\api\Name $name1, \rosette\api\Name $name2)
    {
        $dedup_list = array($name1, $name2);
        $this->beConstructedWith($dedup_list);
        $this->shouldNotThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_has_valid_threshold(\rosette\api\Name $name1, \rosette\api\Name $name2)
    {
        $dedup_list = array($name1, $name2);
        $this->beConstructedWith($dedup_list, 0.80);
        $this->threshold->shouldBeLike(0.80);
        $this->shouldNotThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_has_invalid_threshold(\rosette\api\Name $name1, \rosette\api\Name $name2)
    {
        $dedup_list = array($name1, $name2);
        $this->beConstructedWith($dedup_list, 12.34);
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }
}
