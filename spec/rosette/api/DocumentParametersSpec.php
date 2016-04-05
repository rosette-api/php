<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DocumentParametersSpec extends ObjectBehavior
{
    private $sampleStringData = 'Sample string data';

    function it_is_initializable()
    {
        $this->shouldHaveType('rosette\api\DocumentParameters');
    }

    function it_validates_no_content_or_content_uri()
    {
        $this->shouldThrow('rosette\api\RosetteException')->duringValidate();
    }

    function it_validates_no_content_and_content_uri()
    {
        $this->content = 'content';
        $this->contentUri = 'contentUri';
        $this->shouldThrow('rosette\api\RosetteException')->duringValidate();
    }

    function it_loads_document_file()
    {
        $this->loadDocumentFile('path');
        $this->useMultiPart->shouldBe(true);
        $this->content->shouldBe($this->sampleStringData);
    }

    function it_loads_document_string()
    {
        $this->loadDocumentString($this->sampleStringData);
        $this->content->shouldBe($this->sampleStringData);
        $this->useMultiPart->shouldBe(false);
    }

    // These test the abstract RosetteParamsSetBase

    function it_sets_and_gets_a_property()
    {
        $property = 'content';
        $value = 'Sample content';
        $this->Set($property, $value);
        $this->Get($property)->shouldBe($value);
    }

    function it_throws_if_invalid_property()
    {
        $this->shouldThrow('rosette\api\RosetteException')->duringGet('bogus');
    }

    function it_serialized()
    {
        $this->content = 'Sample Content';
        $this->serialize()->shouldBeLike('{"content":"Sample Content"}');

    }


}

namespace rosette\api;

// mock the global function file_get_contents()
function file_get_contents($path)
{
    return 'Sample string data';
}
