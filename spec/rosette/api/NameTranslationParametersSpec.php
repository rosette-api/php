<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NameTranslationParametersSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('rosette\api\NameTranslationParameters');
    }

    function it_has_no_name()
    {
        $this->shouldThrow('rosette\api\RosetteException')->duringValidate();
    }

    function it_has_no_target_language()
    {
        $this->name = "test name";
        $this->shouldThrow('rosette\api\RosetteException')->duringValidate();
    }

    function it_passes_validate()
    {
        $this->name = "test name";
        $this->targetLanguage = "target language";
        $this->shouldNotThrow('rosette\api\RosetteException')->duringValidate();
    }
}
