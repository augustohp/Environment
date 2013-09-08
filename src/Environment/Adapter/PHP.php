<?php

namespace Environment\Adapter;

class PHP implements Behavior\Write,
                     Behavior\Read,
                     Behavior\Available,
                     Behavior\Adapter
{
    public static function isAvailable()
    {
        return true;
    }

    public function get($name)
    {
        return getenv($name);
    }

    public function set($name, $value)
    {
        putenv("${name}=${value}");
    }
}