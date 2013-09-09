<?php

namespace Environment\Exception;

use Environment\Error;

class MissingBehavior extends \BadMethodCallException
{
    public function __construct($adapterInstance, $behaviorInterface, Exception $previousException=null)
    {
        $adapterClassName = get_class($adapterInstance);
        $behaviorinterfaceNameParts = explode('\\', $behaviorInterface);
        $operation = strtolower(array_pop($behaviorinterfaceNameParts));
        $templateExceptionMessage = '\'%s\' has no %s support.';
        $message = sprintf($templateExceptionMessage, $adapterClassName, $operation);
        parent::__construct($message, Error::COMPONENT_ARCHITECTURE, $previousException);
    }
}