<?php

namespace Environment\Source;

use Environment\Exception;

abstract class AbstractSource
{
    /**
     * @var Environment\Source\AbstractSource
     */
    private $nextSource;

    public abstract function isAvailable();
    protected abstract function get($name);

    public function read($name)
    {
        if (false === $this->isAvailable()) {
            throw new Exception\Availiability('Source not available.');
        }

        $returnedValue = $this->get($name);
        if (false === is_null($returnedValue)) {
            return $returnedValue;
        }

        if (false === is_null($this->nextSource)) {
            return $this->getNextSource()->read($name);
        }

        return null;
    }


    public function getNextSource()
    {
        if (empty($this->nextSource)) {
            throw new Exception\Availiability('Next source not set.');
        }

        return $this->nextSource;
    }

    public function setNextSource(AbstractSource $nextSource)
    {
        $this->nextSource = $nextSource;
    }
}
