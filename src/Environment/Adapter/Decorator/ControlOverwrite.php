<?php

namespace Environment\Adapter\Decorator;
use Environment\WriterInterface;
use Environment\ReaderInterface;
use Environment\Exception;

class ControlOverwrite implements WriterInterface, ReaderInterface
{
    const PREVENT = false;
    const ALLOW = true;
    private $decoratedAdapter;
    private $optionAllowOverwrite;

    public function __construct($adapter, $allowOverwrite=self::PREVENT)
    {
        if (!$adapter instanceof WriterInterface && !$adapter instanceof ReaderInterface) {
            $baseExceptionMessage = 'Class \'%s\' does not implement any adapter interface.';
            $exceptionMessage = sprintf($baseExceptionMessage, get_class($adapter));
            throw new \InvalidArgumentException($exceptionMessage);
        }

        $this->decoratedAdapter = $adapter;
        $this->optionAllowOverwrite = (boolean) $allowOverwrite;
    }

    private function hasValue($name)
    {
        try {
            $this->get($name);
            return true;
        } catch (Exception\KeyNotFound $e) {
            return false;
        }
    }

    public function get($name)
    {
        $value = $this->decoratedAdapter->get($name);
        if (empty($value)) {
            throw new Exception\KeyNotFound($name);
        }

        return $value;
    }

    public function set($name, $value)
    {
        if ($this->hasValue($name) && false === $this->optionAllowOverwrite) {
            $exceptionMessage = sprintf('\'%s\' is already set, and overwrite is not allowed.', $name);
            throw new Exception\WriteNotAllowed($exceptionMessage);
        }

        $this->decoratedAdapter->set($name, $value);
    }
}