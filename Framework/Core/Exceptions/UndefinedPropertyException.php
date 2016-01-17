<?php

namespace Exceptions;

/**
 * Exception thrown when trying to get value of an undefined property
 *
 * @author Allen
 */
class UndefinedPropertyException extends \ExceptionManager
{

    public function __construct($className, $propertyName, \Exception $previous = null)
    {
        $message = sprintf("Undefined property %s::%s", $className, $propertyName);
        parent::__construct($message, 0, $previous);
    }


}
