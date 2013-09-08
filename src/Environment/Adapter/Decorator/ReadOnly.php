<?php

namespace Environment\Adapter\Decorator;

use Environment\Adapter\Behavior;
use Environment\Exception;

class ReadOnly implements Behavior\Read, Behavior\Write
{
    protected $decoratedAdapter;

    public function __construct(Behavior\Adapter $adapter)
    {
        $this->decoratedAdapter = $adapter;
    }

    public function get($name)
    {
        return $this->decoratedAdapter->get($name);
    }

    public function set($name, $value)
    {
        throw new Exception\ReadOnly($name);
    }
}