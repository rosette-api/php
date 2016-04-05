<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NameSpec extends ObjectBehavior
{
    function it_should_except_with_no_name()
    {
        $this->beConstructedWith(null);
        $this->shouldThrow('rosette\api\RosetteException')->duringInstantiation();
    }

    function it_validates_arguments()
    {
        $name = 'testName';
        $entityType = 'entityType';
        $language = 'language';
        $script = 'script';
        $this->beConstructedWith($name, $entityType, $language, $script);
        $this->text->shouldBeLike($name);
        $this->entityType->shouldBeLike($entityType);
        $this->language->shouldBeLike($language);
        $this->script->shouldBeLike($script);
    }
}
