<?php

namespace Environment\Source;

/**
 * @group source
 * @coverage Environment\Source\AbstractSource
 */
class AbstractSourceTest extends \PHPUnit_Framework_TestCase
{
    public function assertPreConditions()
    {
        $this->assertTrue(
            class_exists($className = 'Environment\\Source\\ArrayObject'),
            'Expected class do not exist: '.$className
        );
    }

    /**
     * @test
     * @expectedException Environment\Exception\Availiability
     * @expectedExceptionMessage Next source not set.
     */
    public function get_next_source_without_any_source_set_fails()
    {
        $source = $this->getMockForAbstractClass('Environment\\Source\\AbstractSource');
        $source->getNextSource();
    }

    /**
     * @test
     * @depends get_next_source_without_any_source_set_fails
     */
    public function set_and_get_next_source()
    {
        $source = $this->getMockForAbstractClass('Environment\\Source\\AbstractSource');
        $nextSource = $this->getMockForAbstractClass('Environment\\Source\\AbstractSource');
        $this->assertAttributeEmpty(
            $attributeName = 'nextSource',
            $source
        );
        $source->setNextSource($nextSource);
        $this->assertAttributeSame(
            $nextSource,
            $attributeName,
            $source
        );
        $this->assertSame(
            $nextSource,
            $source->getNextSource()
        );
    }

    /**
     * @test
     * @expectedException Environment\Exception\Availiability
     * @expectedExceptionMessage Source not available.
     */
    public function read_unavailable_source_fails()
    {
        $source = $this->getMockForAbstractClass('Environment\\Source\\AbstractSource');
        $this->setSourceAvailiability($source, false);
        $source->read('some-value');
    }

    /**
     * @test
     * @depends read_unavailable_source_fails
     */
    public function read_existing_value()
    {
        $varName = 'someKey';
        $varValue = 'someValue';
        $source = $this->getMockForAbstractClass('Environment\\Source\\AbstractSource');
        $source->expects($this->once())
               ->method('get')
               ->with($this->equalTo($varName))
               ->will($this->returnValue($varValue));
        $this->setSourceAvailiability($source, true);
        $this->assertEquals(
            $varValue,
            $source->read($varName)
        );
    }

    /**
     * @test
     * @depends read_unavailable_source_fails
     * @depends read_existing_value
     */
    public function read_existing_value_that_evaluates_to_empty()
    {
        $varName = 'someKey';
        $varValue = 0; // this value returns TRUE on empty()
        $source = $this->getMockForAbstractClass('Environment\\Source\\AbstractSource');
        $source->expects($this->once())
               ->method('get')
               ->with($this->equalTo($varName))
               ->will($this->returnValue($varValue));
        $this->setSourceAvailiability($source, true);
        $this->assertEquals(
            $varValue,
            $source->read($varName)
        );
    }

    /**
     * @test
     * @depends set_and_get_next_source
     * @depends read_existing_value
     */
    public function read_non_existing_value_with_next_source_set()
    {
        $varName = 'someKey';
        $varValue = null;
        $source = $this->getMockForAbstractClass('Environment\\Source\\AbstractSource');
        $nextSource = $this->getMockForAbstractClass('Environment\\Source\\AbstractSource');
        $this->setSourceAvailiability($source, true);
        $this->setSourceAvailiability($nextSource, true);
        $source->expects($this->once())
               ->method('get')
               ->with($this->equalTo($varName))
               ->will($this->returnValue(null));
        $nextSource->expects($this->once())
                   ->method('get')
                   ->with($this->equalTo($varName))
                   ->will($this->returnValue($varValue));
        $source->setNextSource($nextSource);
        $this->assertEquals(
            $varValue,
            $source->read($varName)
        );
    }

    /**
     * @test
     * @depends set_and_get_next_source
     * @depends read_non_existing_value_with_next_source_set
     * @depends read_existing_value
     */
    public function read_value_that_exist_only_on_next_source()
    {
        $varName = 'someKey';
        $varValue = 'someValue';
        $source = $this->getMockForAbstractClass('Environment\\Source\\AbstractSource');
        $nextSource = $this->getMockForAbstractClass('Environment\\Source\\AbstractSource');
        $this->setSourceAvailiability($source, true);
        $this->setSourceAvailiability($nextSource, true);
        $source->expects($this->once())
               ->method('get')
               ->with($this->equalTo($varName))
               ->will($this->returnValue(null));
        $nextSource->expects($this->once())
                   ->method('get')
                   ->with($this->equalTo($varName))
                   ->will($this->returnValue($varValue));
        $source->setNextSource($nextSource);
        $this->assertEquals(
            $varValue,
            $source->read($varName)
        );
    }

    /**
     * @test
     * @depends set_and_get_next_source
     * @depends read_non_existing_value_with_next_source_set
     * @depends read_existing_value
     * @depends read_value_that_exist_only_on_next_source
     */
    public function read_value_on_first_source_that_exists_on_the_third_source_inside_the_chain()
    {
        $varName = 'someKey';
        $varValue = 'someValue';
        $source = $this->getMockForAbstractClass('Environment\\Source\\AbstractSource');
        $secondSource = $this->getMockForAbstractClass('Environment\\Source\\AbstractSource');
        $thrirdSource = $this->getMockForAbstractClass('Environment\\Source\\AbstractSource');
        $this->setSourceAvailiability($source, true);
        $this->setSourceAvailiability($secondSource, true);
        $this->setSourceAvailiability($thrirdSource, true);
        $source->expects($this->once())
               ->method('get')
               ->with($this->equalTo($varName))
               ->will($this->returnValue(null));
        $secondSource->expects($this->once())
               ->method('get')
               ->with($this->equalTo($varName))
               ->will($this->returnValue(null));
        $thrirdSource->expects($this->once())
                   ->method('get')
                   ->with($this->equalTo($varName))
                   ->will($this->returnValue($varValue));
        $source->setNextSource($secondSource);
        $secondSource->setNextSource($thrirdSource);
        $this->assertEquals(
            $varValue,
            $source->read($varName)
        );
    }

    private function setSourceAvailiability($mockClass, $isAvailable)
    {
        $mockClass->expects($this->once())
                  ->method('isAvailable')
                  ->will($this->returnValue($isAvailable));
    }
}
