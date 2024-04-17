<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use rosette\api\RosetteConstants;

class RecordSimilarityParametersSpec extends ObjectBehavior
{
    public function it_passes_validation($fields, $properties, $records)
    {
        $this->beConstructedWith((array)$fields, (array)$properties, (array)$records);
        $this->shouldNotThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_has_fields_undefined($fields, $properties, $records)
    {
        $this->beConstructedWith((array)null, (array)$properties, (array)$records);
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_has_properties_undefined($fields, $properties, $records)
    {
        $this->beConstructedWith((array)$fields, (array)null, (array)$records);
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_has_records_undefined($fields, $properties, $records)
    {
        $this->beConstructedWith((array)$fields, (array)$properties, (array)null);
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }
}
