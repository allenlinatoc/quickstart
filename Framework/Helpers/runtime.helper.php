<?php

//------------------------------------------------------------------------------
// Helpers for Runtime operations
//------------------------------------------------------------------------------


/**
 * Check if a class is already loaded
 *
 * @param string $class The name of the class, including namespace if necessary
 * @return boolean
 */
function is_class_loaded($class)
{
    return in_array($class, get_declared_classes());
}


/**
 * Check if current OS is Windows
 *
 * @return boolean
 */
function is_windows()
{
    return !in_array(preg_match("/^WIN/i", PHP_OS), [ false, 0 ]);
}