<?php

namespace Environment\Source;

class Apc extends AbstractSource
{
    public function isAvailable()
    {
        return extension_loaded('apc');
    }

    protected function get($name)
    {
        if (false === apc_exists($name)) {
            return null;
        }

        return apc_fetch($name);
    }
}