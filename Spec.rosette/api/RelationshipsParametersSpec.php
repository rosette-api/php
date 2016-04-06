<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RelationshipsParametersSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('rosette\api\RelationshipsParameters');
    }

    public function it_has_accuracy_defined()
    {
        $this->options->shouldHaveKeyWithValue('accuracyMode', 'PRECISION');
    }

    public function it_sets_an_option()
    {
        $this->setOption('test', 'value');
        $this->getOption('test')->shouldBe('value');
    }
}
