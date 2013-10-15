<?php

namespace Environment\Source;

/**
 * @group source
 * @covers Environment\Source\GetEnv
 */
class GetEnvTest extends \PHPUnit_Framework_TestCase
{
    const VAR_NAME = 'someVar';

    public function setUp()
    {
        putenv(self::VAR_NAME.'='); // Clear environment value
    }

    public function assertPreConditions()
    {
        $this->assertTrue(
            class_exists($className = 'Environment\\Source\\GetEnv'),
            'Expected class do not exist: '.$className
        );
    }

    /**
     * @test
     */
    public function is_always_available()
    {
        $source = new GetEnv;
        $this->assertTrue(
            $source->isAvailable()
        );
    }

    /**
     * @test
     */
    public function read_existing_value()
    {
        $source = new GetEnv;
        $varName = self::VAR_NAME;
        $varValue = 'some value';
        putenv("{$varName}={$varValue}");
        $this->assertEquals(
            $varValue,
            $source->read($varName)
        );
    }

    /**
     * @test
     */
    public function read_missing_value_returns_null_instead_of_false()
    {
        $source = new GetEnv;
        $varName = 'someValue';
        $expectedValue = null;
        $this->assertSame(
            $expectedValue,
            $source->read($varName)
        );
    }
}