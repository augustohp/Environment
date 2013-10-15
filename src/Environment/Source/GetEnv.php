<?php

namespace Environment\Source;

class GetEnv extends AbstractSource
{
    public function isAvailable()
    {
        return true;
    }

    protected function get($name)
    {
        $returnedValue = getenv($name);
        if (false === $returnedValue) {
            return null;
        }

        return $returnedValue;
    }
}