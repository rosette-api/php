<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use rosette\api\RosetteConstants;

class RecordSimilarityParameterSpec extends ObjectBehavior
{
    public function it_passes_validation($fields, $properties, $records)
    {
        $this->beConstructedWith($fields, $properties, $records);
        $this->shouldNotThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_has_fields_undefined($fields, $properties, $records)
    {
        $this->beConstructedWith(null, $properties, $records);
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_has_properties_undefined($fields, $properties, $records)
    {
        $this->beConstructedWith($fields, null, $records);
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_has_records_undefined($fields, $properties, $records)
    {
        $this->beConstructedWith($fields, $properties, null);
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }
}
