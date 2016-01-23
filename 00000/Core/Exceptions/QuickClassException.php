<?php


namespace Exceptions;

/**
 * Exception thrown during initialization
 */
class QuickClassException extends \ExceptionManager
{

    public function __construct($paramName, $className, \Exception $previous = null)
    {
        $message = sprintf("Property $className::%s does not exist. Enable passive to autocreate property", $paramName);
        parent::__construct($message, 0, $previous);
    }

}
