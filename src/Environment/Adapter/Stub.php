<?php

namespace Environment\Adapter;

use Environment\WriterInterface;
use Environment\ReaderInterface;

class Stub implements WriterInterface, ReaderInterface
{
    private $environmentData;

    public function __construct(array $environmentData=array())
    {
        $this->environmentData = $environmentData;
    }

    public function get($name)
    {
        if (isset($this->environmentData[$name])) {
            return $this->environmentData[$name];
        }

        return null;   
    }

    public function set($name, $value)
    {
        $this->environmentData[$name] = $value;
    }
}