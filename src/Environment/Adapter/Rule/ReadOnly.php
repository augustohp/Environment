<?php

namespace Environment\Adapter\Rule;

use Environment\Adapter;
use Environment\Adapter\Behavior;
use Environment\Exception;

class ReadOnly implements Behavior\Read,
                          Behavior\Write,
                          Behavior\Delete
{
    protected $composedAdapter;

    public function __construct(Behavior\Read $adapter)
    {
        if (!$adapter instanceof Adapter\Mediator) {
            $adapter = new Adapter\Mediator($adapter);
        }

        $this->composedAdapter = $adapter;
    }

    public function get($name)
    {
        return $this->composedAdapter->get($name);
    }

    public function set($name, $value)
    {
        throw new Exception\ReadOnly($name);
    }

    public function delete($name)
    {
        throw new Exception\ReadOnly($name);
    }
}