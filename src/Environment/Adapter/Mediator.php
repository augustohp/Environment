<?php

namespace Environment\Adapter;

use Environment\Exception;
use Environment\Error;

/**
 * Encapsulates all possible adapter behaviors.
 */
class Mediator implements Behavior\Available,
                          Behavior\Delete,
                          Behavior\KeyExists,
                          Behavior\Read,
                          Behavior\Write
{
    const BEHAVIORS_NAMESPACE = 'Environment\\Adapter\\Behavior';

    protected $composedAdapter;

    public function __construct(Behavior\Adapter $adapter)
    {
        $this->composedAdapter = $adapter;
    }

    public function hasBehavior($baseInterfaceName)
    {
        $adapter = $this->composedAdapter;
        $fullInterfaceName = sprintf(self::BEHAVIORS_NAMESPACE.'\\%s', $baseInterfaceName);
        if (false === interface_exists($fullInterfaceName)) {
            $exceptionMessage = sprintf("Interface '%s' does not exist.", $fullInterfaceName);
            throw new \BadMethodCallException($exceptionMessage, Error::COMPONENT_CONFIGURATION);
        }

        if ($adapter instanceof $fullInterfaceName) {
            return true;
        }

        throw new Exception\MissingBehavior($adapter, $fullInterfaceName);
    }

    public function isAvailable()
    {
        $this->hasBehavior('Available');
        return $this->composedAdapter->isAvailable();
    }

    public function get($name)
    {
        $this->hasBehavior('Read');
        return $this->composedAdapter->get($name);
    }

    public function set($name, $value)
    {
        $this->hasBehavior('Write');
        return $this->composedAdapter->set($name, $value);
    }

    public function delete($name)
    {
        $this->hasBehavior('Delete');
        return $this->composedAdapter->delete($name);
    }

    public function hasKey($name)
    {
        $this->hasBehavior('KeyExists');
        return $this->composedAdapter->hasKey($name);
    }
}