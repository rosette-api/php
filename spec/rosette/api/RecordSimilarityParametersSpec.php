<?php

namespace spec\rosette\api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use rosette\api\RecordSimilarityComparisonMethod;
use rosette\api\RosetteConstants;

class RecordSimilarityParametersSpec extends ObjectBehavior
{
    public function it_passes_validation($fields, $properties, $records)
    {
        $this->beConstructedWith((array)$fields, (array)$properties, (array)$records);
        $this->shouldNotThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_has_records_undefined($fields, $properties, $records)
    {
        $this->beConstructedWith((array)$fields, (array)$properties, (array)null);
        $this->shouldThrow(RosetteConstants::$RosetteExceptionFullClassName)->duringValidate();
    }

    public function it_serializes_the_record_similarity_comparison()
    {
        $fields = array('primaryName' => array('type' => 'rni_name', 'weight' => 0.5));
        $properties = array('threshold' => 0.8);
        $records = array(
            'left' => array(array('primaryName' => 'Ethan R')),
            'right' => array(array('primaryName' => 'Evan R')),
        );

        $this->beConstructedWith($fields, $properties, $records, RecordSimilarityComparisonMethod::N_TO_M);
        $this->serialize(array())->shouldBe(json_encode(array(
            'fields' => $fields,
            'properties' => $properties,
            'records' => $records,
            'comparisonMethod' => 'n_to_m',
        )));
    }

    public function it_serializes_one_to_n_comparison()
    {
        $fields = array('primaryName' => array('type' => 'rni_name', 'weight' => 0.5));
        $properties = array('threshold' => 0.8);
        $records = array(
            'left' => array(array('primaryName' => 'Ethan R')),
            'right' => array(
                array('primaryName' => 'Evan R'),
                array('primaryName' => 'Ethan Roberts'),
            ),
        );

        $this->beConstructedWith($fields, $properties, $records, RecordSimilarityComparisonMethod::ONE_TO_N);
        $this->serialize(array())->shouldBe(json_encode(array(
            'fields' => $fields,
            'properties' => $properties,
            'records' => $records,
            'comparisonMethod' => 'one_to_n',
        )));
    }

    public function it_defaults_to_one_to_one_comparison()
    {
        $records = array('left' => array(array('primaryName' => 'Ethan R')));

        $this->beConstructedWith(array(), array(), $records);
        $this->serialize(array())->shouldBe(json_encode(array(
            'records' => $records,
            'comparisonMethod' => 'one_to_one',
        )));
    }
}
