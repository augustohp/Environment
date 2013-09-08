<?php

namespace Environment\Exception;

use Environment\Error;

class WriteNotAllowed extends \BadMethodCallException
{
    public function __construct($name, $code=0, $previousException=null)
    {
        $baseMessage = '\'%s\' is already set, overwrite is not allowed.';
        $message = sprintf($baseMessage, $name);
        parent::__construct($message, Error::WRITE_NOT_ALLOWED, $previousException);
    }
}