<?php

namespace Exceptions;

/**
 * Exception thrown when loading route
 *
 * @author Allen
 */
class RouteLoadException extends \ExceptionManager
{

    public function __construct(\Exception $previous = null)
    {
        parent::__construct(sprintf("Failed to load route file \"%s\"", MY_ETC_PATH . "/routes.bsv"));
    }

}
