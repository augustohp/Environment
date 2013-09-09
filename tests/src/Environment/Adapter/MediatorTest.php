<?php

namespace Environment\Adapter;

class MediatorTest extends \PHPUnit_Framework_TestCase
{
    const SUBJECT_CLASS = 'Environment\\Adapter\\Mediator';
    public function assertPreConditions()
    {
        $this->assertTrue(
            class_exists(self::SUBJECT_CLASS),
            'Class does not exist: '.self::SUBJECT_CLASS
        );
    }

    /**
     * @test
     */
    public function new_instance_with_adapter()
    {
        $adapter = new Mediator(new Stub);
        $this->assertInstanceOf(
            self::SUBJECT_CLASS,
            $adapter
        );
        $this->assertAttributeInstanceOf(
            'Environment\\Adapter\Behavior\\Adapter',
            'composedAdapter',
            $adapter
        );
    }

    /**
     * @test
     * @depends new_instance_with_adapter
     * @todo Really get all available behaviors, instead of declaring them.
     */
    public function encapsulate_all_available_behaviors()
    {
        $behaviorNamespace = 'Environment\\Adapter\\Behavior';
        $availiableBehaviors = [
            'Adapter',
            'Available',
            'Delete',
            'KeyExists',
            'Read',
            'Write'
        ];
        $adapter = new Mediator(new Stub);
        foreach ($availiableBehaviors as $behaviorName) {
            $interfaceName = $behaviorNamespace.'\\'.$behaviorName;
            $this->assertInstanceOf(
                $interfaceName,
                $adapter
            );
        }
    }

    /**
     * @test
     * @depends encapsulate_all_available_behaviors
     * @expectedException Environment\Exception\MissingBehavior
     * @expectedExceptionMessage has no read support.
     */
    public function try_not_suported_read_on_adapter()
    {
        $adapter = new Mediator(new BadAdapter());
        $adapter->get('something');
    }

    /**
     * @test
     * @depends try_not_suported_read_on_adapter
     * @expectedException Environment\Exception\MissingBehavior
     * @expectedExceptionMessage has no write support.
     */
    public function try_not_supported_write_on_adapter()
    {
        $adapter = new Mediator(new BadAdapter());
        $adapter->set('something', 'somewhere');
    }

    /**
     * @test
     * @depends try_not_suported_read_on_adapter
     * @expectedException Environment\Exception\MissingBehavior
     * @expectedExceptionMessage has no delete support.
     */
    public function try_not_supported_delete_on_adapter()
    {
        $adapter = new Mediator(new BadAdapter());
        $adapter->delete('something');
    }

    /**
     * @test
     * @depends try_not_suported_read_on_adapter
     * @expectedException Environment\Exception\MissingBehavior
     * @expectedExceptionMessage has no keyexists support.
     */
    public function try_not_supported_hasKey_on_adapter()
    {
        $adapter = new Mediator(new BadAdapter());
        $adapter->hasKey('something');
    }

    /**
     * @test
     * @depends new_instance_with_adapter
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Interface 'Environment\Adapter\Behavior\Booboo' does not exist.
     */
    public function try_hasBehavior_with_missing_interface()
    {
        $adapter = new Mediator(new Stub);
        $adapter->hasBehavior('Booboo');
    }

    /**
     * @test
     * @depends new_instance_with_adapter
     */
    public function read_from_capable_adapter()
    {
        $expectedKey = 'name';
        $expectedValue = 'live';
        $environment = [$expectedKey=>$expectedValue];
        $adapter = new Mediator(new Stub($environment));
        $this->assertEquals(
            $expectedValue,
            $adapter->get($expectedKey)
        );
    }

    /**
     * @test
     * @depends new_instance_with_adapter
     */
    public function hasKey_from_capable_adapter()
    {
        $expectedKey = 'name';
        $expectedValue = 'live';
        $environment = [$expectedKey=>$expectedValue];
        $adapter = new Mediator(new Stub($environment));
        $this->assertTrue(
            $adapter->hasKey($expectedKey)
        );
    }

    /**
     * @test
     * @depends hasKey_from_capable_adapter
     * @depends read_from_capable_adapter
     */
    public function write_on_capacble_adapter()
    {
        $expectedKey = 'name';
        $expectedValue = 'live';
        $adapter = new Mediator(new Stub);
        $this->assertFalse(
            $adapter->hasKey($expectedKey)
        );
        $adapter->set($expectedKey, $expectedValue);
        $this->assertEquals(
            $expectedValue,
            $adapter->get($expectedKey)
        );
    }

    /**
     * @test
     * @depends hasKey_from_capable_adapter
     */
    public function delete_on_capable_driver()
    {
        $expectedKey = 'name';
        $expectedValue = 'live';
        $environment = [$expectedKey=>$expectedValue];
        $adapter = new Mediator(new Stub($environment));
        $this->assertTrue(
            $adapter->hasKey($expectedKey)
        );
        $adapter->delete($expectedKey);
        $this->assertFalse(
            $adapter->hasKey($expectedKey)
        );
    }
}

class BadAdapter implements Behavior\Adapter, Behavior\Available
{
    public function isAvailable()
    {
        return false;
    }
}