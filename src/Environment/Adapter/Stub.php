<?php

namespace Environment\Adapter;

class Stub implements Behavior\Write,
                      Behavior\Read,
                      Behavior\Available,
                      Behavior\Adapter
{
    private $environmentData;

    public function __construct(array $environmentData=array())
    {
        $this->environmentData = $environmentData;
    }

    public static function isAvailable()
    {
        return true;
    }

    public function get($name)
    {
        if (isset($this->environmentData[$name])) {
            return $this->environmentData[$name];
        }

        return null;   
    }

    public function set($name, $value)
    {
        $this->environmentData[$name] = $value;
    }
}