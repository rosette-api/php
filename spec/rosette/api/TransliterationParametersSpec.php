<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransliterationParametersSpec extends ObjectBehavior
{
    public function it_passes_validate()
    {
        $this->beConstructedWith("content", "tl", "ts", "sl", "ss");
        $this->shouldNotThrow('rosette\api\RosetteException')->duringValidate();
    }
    public function it_is_initializable()
    {
        $this->beConstructedWith("content", "tl", "ts", "sl", "ss");
        $this->shouldHaveType('rosette\api\TransliterationParameters');
    }
}
