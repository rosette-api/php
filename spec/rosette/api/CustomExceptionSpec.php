<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CustomExceptionSpec extends ObjectBehavior
{
    public function it_should_except_with_no_message()
    {
        $this->shouldThrow('\Exception')->duringInstantiation();
    }

    public function it_validates_arguments()
    {
        $message = 'test exception message';
        $code = 99;
        $this->beConstructedWith($message, $code);
        $this->getMessage()->shouldBeLike("[${code}]: ${message}");
        $this->getCode()->shouldBeLike($code);
    }

    public function it_validates_non_numeric_code()
    {
        $code = 'string code';
        $this->beConstructedWith('test message', $code);
        $this->getCode()->shouldBeLike(0);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('rosette\api\CustomException');
    }
}
