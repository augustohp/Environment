<?php

namespace Environment\Adapter;

class Stub implements Behavior\Write,
                      Behavior\Read,
                      Behavior\Available,
                      Behavior\Delete,
                      Behavior\KeyExists
{
    private $environmentData;

    public function __construct(array $environmentData=array())
    {
        $this->environmentData = $environmentData;
    }

    public function isAvailable()
    {
        return true;
    }

    public function get($name)
    {
        if ($this->hasKey($name)) {
            return $this->environmentData[$name];
        }

        return null;   
    }

    public function set($name, $value)
    {
        $this->environmentData[$name] = $value;
    }

    public function hasKey($name)
    {
        return isset($this->environmentData[$name]);
    }

    public function delete($name)
    {
        unset($this->environmentData[$name]);
    }
}