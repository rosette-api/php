<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NameDeduplicationParametersSpec extends ObjectBehavior
{
    public function it_passes_validation(\rosette\api\Name $name1, \rosette\api\Name $name2)
    {
        $dedup_list = array($name1, $name2);
        $this->beConstructedWith($dedup_list);
        $this->shouldNotThrow('rosette\api\RosetteException')->duringValidate();
    }

    public function it_has_valid_threshold(\rosette\api\Name $name1, \rosette\api\Name $name2)
    {
        $dedup_list = array($name1, $name2);
        $this->beConstructedWith($dedup_list, 0.80);
        $this->threshold->shouldBeLike(0.80);
        $this->shouldNotThrow('rosette\api\RosetteException')->duringValidate();
    }

    public function it_has_invalid_threshold(\rosette\api\Name $name1, \rosette\api\Name $name2)
    {
        $dedup_list = array($name1, $name2);
        $this->beConstructedWith($dedup_list, 12.34);
        $this->shouldThrow('rosette\api\RosetteException')->duringValidate();
    }
}
