<?php

namespace Environment\Adapter;

class Apc implements Behavior\Write,
                     Behavior\Read,
                     Behavior\Available,
                     Behavior\KeyExists,
                     Behavior\Delete
{
    public static function isAvailable()
    {
        return extension_loaded('apc');
    }

    public function hasKey($name)
    {
        return apc_exists($name);
    }

    public function delete($name)
    {
        return apc_delete($name);
    }

    public function get($name)
    {
        $value = apc_fetch($name);
        if ($value === false) {
            return null;
        }

        return $value;
    }

    public function set($name, $value)
    {
        if ($this->hasKey($name)) {
            $this->delete($name);
        }

        return apc_add($name, $value);
    }
}