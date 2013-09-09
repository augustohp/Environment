<?php

namespace Environment\Adapter;

class PHP implements Behavior\Write,
                     Behavior\Read,
                     Behavior\Available,
                     Behavior\Delete
{
    public function isAvailable()
    {
        return true;
    }

    public function get($name)
    {
        return getenv($name);
    }

    public function set($name, $value)
    {
        return putenv("${name}=${value}");
    }

    public function delete($name)
    {
        return putenv("${name}");
    }
}