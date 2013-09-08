<?php

namespace Environment\Adapter\Rule;

use Environment\Adapter\Behavior;
use Environment\Exception;

class PreventOverwrite implements Behavior\Write
{
    private $decoratedAdapter;

    public function __construct(Behavior\Write $adapter)
    {
        $this->decoratedAdapter = $adapter;
    }

    public function set($name, $value)
    {
        $adapter = $this->decoratedAdapter;
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