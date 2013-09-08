<?php

namespace Environment\Adapter\Behavior;

interface Read extends Adapter
{
    public function get($name);
}