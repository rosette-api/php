<?php
namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RosetteRequestSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('\rosette\api\RosetteRequest');
    }

    public function it_sends_a_request()
    {
        $this->makeRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any(), Argument::any())->shouldBeBool();
    }

    public function it_returns_a_code()
    {
        $this->getResponseCode()->shouldBeInteger();
    }

    public function it_returns_a_response()
    {
        $this->getResponse()->shouldBeArray();
    }
}
