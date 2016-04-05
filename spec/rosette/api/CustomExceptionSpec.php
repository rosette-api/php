<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CustomExceptionSpec extends ObjectBehavior
{
    function it_should_except_with_no_message()
    {
        $this->shouldThrow('\Exception')->duringInstantiation();
    }

    function it_validates_arguments()
    {
        $message = 'test exception message';
        $code = 99;
        $this->beConstructedWith($message, $code);
        $this->getMessage()->shouldBeLike($message);
        $this->getCode()->shouldBeLike($code);
    }

    function it_validates_non_numeric_code()
    {
        $code = 'string code';
        $this->beConstructedWith('test message', $code);
        $this->getCode()->shouldBeLike(0);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('rosette\api\CustomException');
    }
}
