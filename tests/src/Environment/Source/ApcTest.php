<?php

namespace Environment\Source;

/**
 * @group source
 * @covers Environment\Source\Apc
 */
class ApcTest extends \PHPUnit_Framework_TestCase
{
    const VAR_NAME = 'some-name';
    private $php;

    public function setUp()
    {
        $this->php = \PHPUnit_Extension_FunctionMocker::start($this, __NAMESPACE__)
             ->mockFunction('extension_loaded')
             ->mockFunction('apc_exists')
             ->mockFunction('apc_fetch')
             ->getMock();
    }

    public function assertPreConditions()
    {
        $this->assertTrue(
            class_exists($className = 'Environment\\Source\\Apc'),
            'Expected class do not exist: '.$className
        );
    }

    /**
     * @test
     */
    public function is_not_available()
    {
        $this->php->expects($this->once())
                  ->method('extension_loaded')
                  ->will($this->returnValue(false));
        $source = new Apc;
        $this->assertFalse($source->isAvailable());
    }

    /**
     * @test
     */
    public function is_available()
    {
        $this->php->expects($this->once())
                  ->method('extension_loaded')
                  ->will($this->returnValue(true));
        $source = new Apc;
        $this->assertTrue($source->isAvailable());
    }

    /**
     * @test
     * @depends is_available
     */
    public function read_existing_value()
    {
        $this->php->expects($this->once())
                  ->method('extension_loaded')
                  ->will($this->returnValue(true));
        $this->php->expects($this->once())
                  ->method('apc_exists')
                  ->with(self::VAR_NAME)
                  ->will($this->returnValue(true));
        $this->php->expects($this->once())
                  ->method('apc_fetch')
                  ->with(self::VAR_NAME)
                  ->will($this->returnValue($expectedValue = 'some value'));
        $source = new Apc;
        $this->assertEquals(
            $expectedValue,
            $source->read(self::VAR_NAME)
        );
    }

    /**
     * @test
     */
    public function read_non_existing_value_returns_null()
    {
        $this->php->expects($this->once())
                  ->method('extension_loaded')
                  ->will($this->returnValue(true));
        $this->php->expects($this->once())
                  ->method('apc_exists')
                  ->with(self::VAR_NAME)
                  ->will($this->returnValue(false));
        $source = new Apc;
        $this->assertEquals(
            $expectedValue = null,
            $source->read(self::VAR_NAME)
        );
    }
}