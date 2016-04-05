<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RelationshipsParametersSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('rosette\api\RelationshipsParameters');
    }

    function it_has_accuracy_defined()
    {
        $this->options->shouldHaveKeyWithValue('accuracyMode', 'PRECISION');
    }

    function it_sets_an_option()
    {
        $this->setOption('test', 'value');
        $this->getOption('test')->shouldBe('value');
    }

}
