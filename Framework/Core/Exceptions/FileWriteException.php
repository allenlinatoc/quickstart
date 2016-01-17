<?php

namespace Exceptions;

/**
 * Exception thrown when file write failed
 *
 * @author Allen
 */
class FileWriteException extends \ExceptionManager
{

    public function __construct($path, \Exception $previous = null)
    {
        parent::__construct("Failed to write contents to \"$path\"", 0, $previous);
    }


}
