<?php

namespace Exceptions;
use ExceptionManagers\ReasonableExceptionManager;

/**
 * Exception thrown when unserialization failed
 *
 * @author Allen
 */
class UnserializeException extends ReasonableExceptionManager
{

    const REASON_NOT_OBJECT         = "Object expectation not satisfied after unserialization";
    const REASON_INCOMPLETE_CLASS   = "PHP Incomlete Class after unserialization";
    const REASON_CLASS_UNMATCHED    = "Family of source class is different";


    public function __construct($reason, $code = 0, \Exception $previous = null)
    {
        $message = str("Unserialization failed: {0}", $reason);
        parent::__construct($reason, $message, $code, $previous);
    }

}
