<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DocumentParametersSpec extends ObjectBehavior
{
    private $sampleStringData = 'Sample string data';

    public function it_is_initializable()
    {
        $this->shouldHaveType('rosette\api\DocumentParameters');
    }

    public function it_validates_no_content_or_content_uri()
    {
        $this->shouldThrow('rosette\api\RosetteException')->duringValidate();
    }

    public function it_validates_no_content_and_content_uri()
    {
        $this->content = 'content';
        $this->contentUri = 'contentUri';
        $this->shouldThrow('rosette\api\RosetteException')->duringValidate();
    }

    public function it_loads_document_file()
    {
        $this->loadDocumentFile('path');
        $this->getMultiPartContent()->shouldNotBe('');
        $this->content->shouldBe('');
    }

    public function it_loads_document_string()
    {
        $this->loadDocumentString($this->sampleStringData);
        $this->content->shouldBe($this->sampleStringData);
        $this->getMultiPartContent()->shouldBe('');
    }

    // These test the abstract RosetteParamsSetBase

    public function it_sets_and_gets_a_property()
    {
        $property = 'content';
        $value = 'Sample content';
        $this->Set($property, $value);
        $this->Get($property)->shouldBe($value);
    }

    public function it_throws_if_invalid_property()
    {
        $this->shouldThrow('rosette\api\RosetteException')->duringGet('bogus');
    }

    public function it_serialized()
    {
        $options = array();
        $this->content = 'Sample Content';
        $this->serialize($options)->shouldBeLike('{"content":"Sample Content"}');
    }

    public function it_serialized_with_options()
    {
        $options = array();
        $this->content = 'Sample Content';
        $options["test"] = "foo";
        $this->serialize($options)->shouldBeLike('{"content":"Sample Content","options":{"test":"foo"}}');
    }
}

namespace rosette\api;

// mock the global function file_get_contents()
function file_get_contents($path)
{
    return 'Sample string data';
}
