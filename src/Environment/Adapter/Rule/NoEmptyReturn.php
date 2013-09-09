<?php

namespace Environment\Adapter\Rule;

use Environment\Adapter;
use Environment\Adapter\Behavior;
use Environment\Exception;

class NoEmptyReturn implements Behavior\Read
{
    protected $decoratedAdapter;

    public function __construct(Behavior\Read $adapter)
    {
        if (!$adapter instanceof Adapter\Mediator) {
            $adapter = new Adapter\Mediator($adapter);
        }

        $this->decoratedAdapter = $adapter;
    }

    public function hasValue($name)
    {
        $value = $this->decoratedAdapter->get($name);
        return (!is_null($value)) && (strlen($value) > 0);
    }

    public function get($name)
    {
        if (false === $this->hasValue($name)) {
            throw new Exception\KeyNotFound($name);
        }

        return $this->decoratedAdapter->get($name);
    }
}