<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Exceptions;

/**
 * Exception thrown when a file was not found
 *
 * @author Allen
 */
class FileNotFoundException extends \ExceptionManager
{


    public function __construct($path, \Exception $previous = null)
    {
        parent::__construct("File \"$path\" not found", 0, $previous);
    }


}
