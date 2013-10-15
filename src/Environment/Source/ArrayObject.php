<?php

namespace Environment\Source;

class ArrayObject extends AbstractSource
{
    private $environmentData;
    private $nextSource;

    public function __construct(array $environmentData=array())
    {
        $this->environmentData = $environmentData;
    }

    public function isAvailable()
    {
        return true;
    }

    protected function get($varName)
    {
        if (isset($this->environmentData[$varName])) {
            return $this->environmentData[$varName];
        }

        return null;
    }
}
