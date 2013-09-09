<?php

namespace Environment\Adapter\Rule;

use Environment\Adapter\Behavior;
use Environment\Exception;
use Environment\Error;

class AvailableOnly implements Behavior\Available,
                               Behavior\Read,
                               Behavior\Write,
                               Behavior\Delete,
                               Behavior\KeyExists
{
    protected $decoratedAdapter;

    public function __construct(Behavior\Available $adapter)
    {
        $this->decoratedAdapter = $adapter;
    }

    private function checkAdapterIsAvailable()
    {
        if ($this->decoratedAdapter->isAvailable()) {
            return true;
        }

        $className = get_class($this->decoratedAdapter);
        $message = sprintf('\'%s\' is not availiable to use.', $className);
        $code = Error::ADAPTER_NOT_AVAILABLE;
        throw new Exception\Availiability($message, $code);
    }

    private function checkBehaviorSupport($baseInterfaceName)
    {
        $adapter = $this->decoratedAdapter;
        $fullInterfaceName = sprintf('Environment\\Adapter\\Behavior\\%s', $baseInterfaceName);
        if ($adapter instanceof $fullInterfaceName) {
            return true;
        }

        $classNameParts = explode('\\', $fullInterfaceName);
        $operation = array_pop($classNameParts);
        $operation = strtolower($operation);
        $templateExceptionMessage = '\'%s\' has no %s support.';
        $message = sprintf($templateExceptionMessage, get_class($adapter), $operation);
        throw new Exception\Availiability($message, Error::COMPONENT_CONFIGURATION);
    }

    public function isAvailable()
    {
        return $this->decoratedAdapter->isAvailable();
    }

    public function get($name)
    {
        $this->checkBehaviorSupport('Read');
        $this->checkAdapterIsAvailable();
        return $this->decoratedAdapter->get($name);
    }

    public function set($name, $value)
    {
        $this->checkBehaviorSupport('Write');
        $this->checkAdapterIsAvailable();
        return $this->decoratedAdapter->set($name, $value);
    }

    public function delete($name)
    {
        $this->checkBehaviorSupport('Delete');
        $this->checkAdapterIsAvailable();
        return $this->decoratedAdapter->delete($name);
    }

    public function hasKey($name)
    {
        $this->checkBehaviorSupport('KeyExists');
        $this->checkAdapterIsAvailable();
        return $this->decoratedAdapter->hasKey($name);
    }
}