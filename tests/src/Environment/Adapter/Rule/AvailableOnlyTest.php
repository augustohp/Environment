<?php

namespace Environment\Adapter\Rule;

use Environment\Adapter;
use Environment\Adapter\Behavior;

class AvailableOnlyTest extends \PHPUnit_Framework_TestCase
{
    const AVAILABLE_INTERFACE = 'Environment\\Adapter\\Behavior\\Available';

    public function assertPreConditions()
    {
        $this->assertTrue(
            class_exists($className = 'Environment\\Adapter\\Rule\\AvailableOnly'),
            'Class does not exist: '.$className
        );
        $this->assertTrue(
            interface_exists($interfaceName = self::AVAILABLE_INTERFACE),
            'Interface does not exist: '.$className
        );
    }

    /**
     * @test
     * @dataProvider provideAlwaysAvailableAdapter
     */
    public function always_available_adapters($className)
    {
        $adapter = new AvailableOnly(new $className);
        $this->assertInstanceOf(
            self::AVAILABLE_INTERFACE,
            $adapter
        );
        $this->assertTrue(
            $adapter->isAvailable(),
            sprintf('Adapter "%s" should always be available.', $className)
        );
    }

    public function provideAlwaysAvailableAdapter()
    {
        return [
            ['Environment\\Adapter\\PHP'],
            ['Environment\\Adapter\\Stub']
        ];
    }

    /**
     * @test
     * @depends always_available_adapters
     */
    public function read_existing_key_from_always_available_adapter()
    {
        $name = 'envname';
        $value = 'unittest';
        $adapter = new AvailableOnly(new Adapter\Stub([$name=>$value]));
        $this->assertEquals(
            $expectedValue = $value,
            $resultValue =$adapter->get($name),
            sprintf('Expected key "%s" to have "%s", but got "%s" instead.', $name, $expectedValue, $resultValue)
        );
    }

    /**
     * @test
     * @expectedException Environment\Exception\Availiability
     * @expectedExceptionMessage has no read support.
     */
    public function try_not_suported_read_on_adapter()
    {
        $adapter = new AvailableOnly(new BadAdapter());
        $adapter->get('something');
    }

    /**
     * @test
     * @depends try_not_suported_read_on_adapter
     * @expectedException Environment\Exception\Availiability
     * @expectedExceptionMessage has no write support.
     */
    public function try_not_supported_write_on_adapter()
    {
        $adapter = new AvailableOnly(new BadAdapter());
        $adapter->set('something', 'somewhere');
    }

    /**
     * @test
     * @depends try_not_suported_read_on_adapter
     * @expectedException Environment\Exception\Availiability
     * @expectedExceptionMessage has no delete support.
     */
    public function try_not_supported_delete_on_adapter()
    {
        $adapter = new AvailableOnly(new BadAdapter());
        $adapter->delete('something');
    }

    /**
     * @test
     * @depends try_not_suported_read_on_adapter
     * @expectedException Environment\Exception\Availiability
     * @expectedExceptionMessage has no keyexists support.
     */
    public function try_not_supported_hasKey_on_adapter()
    {
        $adapter = new AvailableOnly(new BadAdapter());
        $adapter->hasKey('something');
    }

    /**
     * @test
     * @expectedException Environment\Exception\Availiability
     * @expectedExceptionMessage is not availiable to use.
     */
    public function try_read_on_unavailable_adapter()
    {
        $adapter = new AvailableOnly(new Adapter\Unavailable());
        $adapter->get('something');
    }

    /**
     * @test
     * @expectedException Environment\Exception\Availiability
     * @expectedExceptionMessage is not availiable to use.
     */
    public function try_write_on_unavailable_adapter()
    {
        $adapter = new AvailableOnly(new Adapter\Unavailable());
        $adapter->set('something', 'somewhere');
    }

    /**
     * @test
     * @expectedException Environment\Exception\Availiability
     * @expectedExceptionMessage is not availiable to use.
     */
    public function try_delete_on_unavailable_adapter()
    {
        $adapter = new AvailableOnly(new Adapter\Unavailable());
        $adapter->delete('something');
    }

    /**
     * @test
     * @expectedException Environment\Exception\Availiability
     * @expectedExceptionMessage is not availiable to use.
     */
    public function try_hasKey_on_unavailable_adapter()
    {
        $adapter = new AvailableOnly(new Adapter\Unavailable());
        $adapter->hasKey('something');
    }
}

class BadAdapter implements Behavior\Adapter, Behavior\Available
{
    public function isAvailable()
    {
        return false;
    }
}