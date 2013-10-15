<?php

namespace Environment\Source;

/**
 * @group source
 * @coverage Environment\Source\ArrayObject
 */
class ArrayObjectTest extends \PHPUnit_Framework_TestCase
{
    const SUBJECT_CLASS = 'Environment\\Source\ArrayObject';
    const DATA_ATTRIBUTE = 'environmentData';

    public function assertPreConditions()
    {
        $this->assertTrue(
            class_exists(self::SUBJECT_CLASS),
            'Class does not exists: '.self::SUBJECT_CLASS
        );
    }

    /**
     * @test
     */
    public function new_instance_with_no_args()
    {
        $class = self::SUBJECT_CLASS;
        $source = new $class;
        $this->assertInstanceOf(
            self::SUBJECT_CLASS,
            $source
        );
    }

    /**
     * @test
     */
    public function new_instance_with_valid_value()
    {
        $environmentVariables = ['name'=>'live'];
        $class = self::SUBJECT_CLASS;
        $source = new $class($environmentVariables);
        $this->assertAttributeEquals(
            $environmentVariables,
            self::DATA_ATTRIBUTE,
            $source
        );

        return $source;
    }

    /**
     * @test
     * @depends new_instance_with_valid_value
     * @coverage Environment\Source\ArrayObject
     */
    public function read_existing_value($source)
    {
        $this->assertEquals(
            'live',
            $source->read('name')
        );
    }

    /**
     * @test
     * @depends read_existing_value
     */
    public function read_missing_key()
    {
        $class = self::SUBJECT_CLASS;
        $source = new $class;
        $this->assertEquals(
            null,
            $source->read('missing')
        );
    }

    /**
     * @test
     */
    public function is_always_available()
    {
        $class = self::SUBJECT_CLASS;
        $source = new $class;
        $this->assertTrue(
            $source->isAvailable(),
            'Source should always be available.'
        );
    }
}
