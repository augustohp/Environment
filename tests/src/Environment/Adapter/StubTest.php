<?php

namespace Environment\Adapter;

class StubTest extends \PHPUnit_Framework_TestCase
{
    const SUBJECT_CLASS = 'Environment\\Adapter\Stub';
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
        $adapter = new $class;
        $this->assertInstanceOf(
            self::SUBJECT_CLASS,
            $adapter
        );
    }

    /**
     * @test
     */
    public function new_instance_with_valid_value()
    {
        $environmentVariables = ['name'=>'live'];
        $class = self::SUBJECT_CLASS;
        $adapter = new $class($environmentVariables);
        $this->assertAttributeEquals(
            $environmentVariables,
            self::DATA_ATTRIBUTE,
            $adapter
        );

        return $adapter;
    }

    /**
     * @test
     * @depends new_instance_with_valid_value
     */
    public function read_existing_value($adapter)
    {
        $this->assertEquals(
            'live',
            $adapter->get('name')
        );
    }

    /**
     * @test
     * @depends read_existing_value
     */
    public function write_new_value()
    {
        $class = self::SUBJECT_CLASS;
        $adapter = new $class;
        $adapter->set('foo', 'bar');
        $this->assertAttributeEquals(
            ['foo'=>'bar'],
            self::DATA_ATTRIBUTE,
            $adapter
        );
        $this->assertEquals(
            'bar',
            $adapter->get('foo')
        );
    }

    /**
     * @test
     * @depends read_existing_value
     */
    public function read_missing_key()
    {
        $class = self::SUBJECT_CLASS;
        $adapter = new $class;
        $this->assertEquals(
            null,
            $adapter->get('missing')
        );   
    }

    /**
     * @test
     * @depends new_instance_with_valid_value
     */
    public function delete_existing_key()
    {
        $environmentVariables = ['name'=>'live'];
        $class = self::SUBJECT_CLASS;
        $adapter = new $class($environmentVariables);
        $this->assertAttributeEquals(
            $environmentVariables,
            self::DATA_ATTRIBUTE,
            $adapter
        );
        $adapter->delete('name');
        $this->assertAttributeEquals(
            array(),
            self::DATA_ATTRIBUTE,
            $adapter
        );
    }
}