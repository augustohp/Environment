<?php

namespace Environment\Adapter;

use Environment\WriterInterface;
use Environment\ReaderInterface;

class PHP implements WriterInterface, ReaderInterface
{
    public function get($name)
    {
        return getenv($name);
    }

    public function set($name, $value)
    {
        putenv("${name}=${value}");
    }
}