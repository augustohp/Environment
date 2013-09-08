<?php

namespace Environment\Exception;

use Environment\Error;

class ReadOnly extends \BadMethodCallException
{
    public function __construct($name, $code=0, $previousException=null)
    {
        $baseMessage = '\'%s\' cannot be set while on read-only mode.';
        $message = sprintf($baseMessage, $name);
        parent::__construct($message, Error::READ_ONLY, $previousException);
    }
}