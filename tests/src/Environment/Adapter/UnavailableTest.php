<?php

namespace Environment\Adapter;

class UnavailableTest extends \PHPUnit_Framework_TestCase
{
    public function assertPreConditions()
    {
        $this->markTestSkipped('Refactor adapters to source namespace.');
        $this->assertTrue(
            class_exists($className = 'Environment\\Adapter\\Unavailable'),
            'Class does no exist: '.$className
        );
    }

    /**
     * @test
     */
    public function has_all_behaviours()
    {
        $adapter = new Unavailable;
        $this->assertInstanceOf(
            'Environment\\Adapter\\Behavior\\Available',
            $adapter
        );
        $this->assertInstanceOf(
            'Environment\\Adapter\\Behavior\\Adapter',
            $adapter
        );
        $this->assertInstanceOf(
            'Environment\\Adapter\\Behavior\\Read',
            $adapter
        );
        $this->assertInstanceOf(
            'Environment\\Adapter\\Behavior\\Write',
            $adapter
        );
        $this->assertInstanceOf(
            'Environment\\Adapter\\Behavior\\Delete',
            $adapter
        );
        $this->assertInstanceOf(
            'Environment\\Adapter\\Behavior\\KeyExists',
            $adapter
        );
    }

    /**
     * @test
     * @depends has_all_behaviours
     */
    public function is_really_unavailable()
    {
        $adapter = new Unavailable;
        $this->assertFalse($adapter->isAvailable());
    }
}