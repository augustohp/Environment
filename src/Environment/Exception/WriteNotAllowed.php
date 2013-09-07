<?php

namespace Environment\Exception;

use Environment\Error;

class WriteNotAllowed extends \BadMethodCallException
{
    public function __construct($message, $code=0, $previousException=null)
    {
        parent::__construct($message, Error::WRITE_NOT_ALLOWED, $previousException);
    }
}