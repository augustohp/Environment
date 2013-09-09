<?php

namespace Environment\Adapter;

class MediatorTest extends \PHPUnit_Framework_TestCase
{
    public function assertPreConditions()
    {
        $this->assertTrue(
            class_exists($className = 'Environment\\Adapter\\Mediator'),
            'Class does not exist: '.$className
        );
    }

    /**
     * @test
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

    public function try_hasBehavior_with_missing_interface()
    {
        $adapter = new Mediator(new Stub);
        $adapter->hasBehavior('Booboo');
    }
}

class BadAdapter implements Behavior\Adapter, Behavior\Available
{
    public function isAvailable()
    {
        return false;
    }
}