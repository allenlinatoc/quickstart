<?php

namespace Exceptions;

use ExceptionManagers\ValceptionManager;

/**
 * Exception thrown when a dimension was not found
 *
 * @author Allen
 */
class DimensionNotFoundException extends ValceptionManager
{

    public function __construct($array, $dimension, \Exception $previous = null)
    {
        $message = str("Dimension \"{0}\" was not found.");
        parent::__construct($array, $message, 0, $previous);
    }

}
