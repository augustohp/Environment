<?php

namespace Environment\Adapter\Rule;

use Environment\Adapter;
use Environment\Adapter\Behavior;
use Environment\Exception;

class PreventOverwrite implements Behavior\Write
{
    private $composedAdapter;

    public function __construct(Behavior\Write $adapter)
    {
        if (!$adapter instanceof Adapter\Mediator) {
            $adapter = new Adapter\Mediator($adapter);
        }

        $this->composedAdapter = $adapter;
    }

    public function set($name, $value)
    {
        $adapter = $this->composedAdapter;
        if (!$adapter instanceof NoEmptyReturn) {
            $adapter = new NoEmptyReturn($adapter);
        }

        if (($adapter instanceof Behavior\KeyExists && $adapter->hasKey($name))
           || ($adapter->hasValue($name))) {
            throw new Exception\WriteNotAllowed($name);
        }

        $adapter->set($name, $value);
    }
}