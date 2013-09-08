<?php

namespace Environment\Adapter\Behavior;

interface Write extends Adapter
{
    public function set($name, $value);
}