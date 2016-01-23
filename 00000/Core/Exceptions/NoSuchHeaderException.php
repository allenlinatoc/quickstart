<?php

namespace Exceptions;
use ExceptionManagers\ValceptionManager;

/**
 * Exception thrown when such header from a response does not exist
 *
 * @author Allen
 */
class NoSuchHeaderException extends ValceptionManager
{

    public function __construct($headername, Response $response, \Exception $previous = null)
    {
        $message = sprintf("Header \"%s\" does not exist", $headername);
        parent::__construct($response, $message, 0, $previous);
    }

}