<?php

namespace Exceptions;
use ExceptionManagers\ValceptionManager;

/**
 * Exception thrown when a value is not scalar
 *
 * @author Allen
 */
class ScalarValueException extends ValceptionManager
{

    public function __construct($value, \Exception $previous = null)
    {
        $message = str("Non-scalar value when expecting scalar value");
        parent::__construct($value, $message, 0, $previous);
    }

}
