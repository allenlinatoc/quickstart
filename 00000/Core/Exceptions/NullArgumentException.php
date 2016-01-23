<?php

namespace Exceptions;

/**
 * Exception thrown when a required argument is null
 *
 * @author Allen
 */
class NullArgumentException extends \ExceptionManager
{

    /**
     * New NullArgunemtException instance
     *
     * @param string $argumentname  Name of null argument
     * @param \Exception $previous
     */
    public function __construct($argumentname, \Exception $previous = null)
    {
        $message = str('Argument "${0}" is null', ltrim($argumentname, '$'));
        parent::__construct($message, 0, $previous);
    }

}
