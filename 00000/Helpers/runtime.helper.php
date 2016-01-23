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
 * Quickstart's alias for PHP's empty() to include UNDEFINED value checking as well
 *
 * @param mixed $var    Value to be checked
 * @return boolean
 */
function is_empty($var)
{
    return empty($var) || is_undefined($var);
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


/**
 * Check if a variable is undefined
 *
 * @param mixed $var    Value to be checked
 * @return boolean
 */
function is_undefined($var)
{
    return $var === UNDEFINED;
}