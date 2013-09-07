<?php

namespace Environment\Exception;

use Environment\Error;

class KeyNotFound extends \UnexpectedValueException
{
    public function __construct($keyName, $code=0, $previousException=null)
    {
        $exceptionMessage = sprintf('\'%s\' key was not found.', $keyName);
        parent::__construct($exceptionMessage, Error::KEY_NOT_FOUND, $previousException);
    }
}