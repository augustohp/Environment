<?php

namespace Environment\Adapter;

use Environment\WriterInterface;
use Environment\ReaderInterface;
use Environment\Exception;

class Stub implements WriterInterface, ReaderInterface
{
    private $environmentData;
    private $optionAllowOverwrite;

    public function __construct(array $environmentData, $allowOverwrite=false)
    {
        $this->environmentData = $environmentData;
        $this->optionAllowOverwrite = (boolean) $allowOverwrite;
    }

    private function keyExists($name)
    {
        if (isset($this->environmentData[$name])) {
            return true;
        }

        return false;
    }

    public function get($name)
    {
        if (false === $this->keyExists($name)) {
            throw new Exception\KeyNotFound($name);
        }

        return $this->environmentData[$name];
    }

    public function set($name, $value)
    {
        if (true === $this->keyExists($name) && false === $this->optionAllowOverwrite) {
            $exceptionMessage = sprintf('\'%s\' is already set, and overwrite is not allowed.', $name);
            throw new Exception\WriteNotAllowed($exceptionMessage);
        }

        $this->environmentData[$name] = $value;
    }
}