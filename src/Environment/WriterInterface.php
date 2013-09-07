<?php

namespace Environment;

interface WriterInterface
{
    public function set($name, $value);
}