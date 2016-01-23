<?php

namespace Exceptions;

/**
 * Exception thrown when loading an invalid JSON file
 *
 * @author Allen
 */
class JSONLoaderException extends \ExceptionManager
{

    public function __construct($file, \Exception $previous = null)
    {
        $message = sprintf("File \"%s\" is not a valid JSON file", $file);
        parent::__construct($message, 0, $previous);
    }


}
