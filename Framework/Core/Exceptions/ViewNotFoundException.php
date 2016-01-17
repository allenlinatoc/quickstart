<?php

namespace Exceptions;

/**
 * Exception thrown if a requested View does not exist
 *
 * @author Allen
 */
class ViewNotFoundException extends \ExceptionManager
{

    public function __construct(\View $view, \Exception $previous = null)
    {
        $message = str("View \"{0}\" does not exist", $view->getPath());
        parent::__construct($message, 0, $previous);
    }

}
