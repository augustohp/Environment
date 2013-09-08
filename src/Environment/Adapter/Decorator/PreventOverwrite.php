<?php

namespace Environment\Adapter\Decorator;

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

        if ($adapter->hasValue($name)) {
            throw new Exception\WriteNotAllowed($name);
        }

        $this->decoratedAdapter->set($name, $value);
    }
}