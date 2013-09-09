<?php

namespace Environment\Adapter;

/**
 * Always unavailable adapter.
 * Useful when on site maintenance or "offline" mode.
 */
class Unavailable implements Behavior\Available,
                             Behavior\Read,
                             Behavior\Write,
                             Behavior\Delete,
                             Behavior\KeyExists
{
    public function isAvailable()
    {
        return false;
    }

    public function get($name)
    {
        return false;
    }

    public function set($name, $value)
    {
        return false;
    }

    public function delete($name)
    {
        return false;
    }

    public function hasKey($name)
    {
        return false;
    }
}