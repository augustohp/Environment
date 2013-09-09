<?php

namespace Environment\Adapter\Rule;

use Environment\Adapter;
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
        if (!$adapter instanceof Adapter\Mediator) {
            $adapter = new Adapter\Mediator($adapter);
        }

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

    public function isAvailable()
    {
        return $this->decoratedAdapter->isAvailable();
    }

    public function get($name)
    {
        $this->checkAdapterIsAvailable();
        return $this->decoratedAdapter->get($name);
    }

    public function set($name, $value)
    {
        $this->checkAdapterIsAvailable();
        return $this->decoratedAdapter->set($name, $value);
    }

    public function delete($name)
    {
        $this->checkAdapterIsAvailable();
        return $this->decoratedAdapter->delete($name);
    }

    public function hasKey($name)
    {
        $this->checkAdapterIsAvailable();
        return $this->decoratedAdapter->hasKey($name);
    }
}