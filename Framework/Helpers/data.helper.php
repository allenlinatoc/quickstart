<?php



/**
 * Alias of var_dump
 *
 * @param mixed $value      The value to dump
 * @param boolean $return   [optional] If specified, dumped data will be returned as string
 *
 * @return string
 */
function dump($value, $return = false)
{
    if (!$return)
    {
        var_dump($value);
        return;
    }

    ob_start();
    var_dump($value);
    $result = ob_get_clean();

    return $result;
}


/**
 * Dump a value with HTML line-break at the end
 *
 * @param string $value     The value to dump
 * @param boolean $return   [optional] If specified, dumped data will be returned as string
 * @return string
 */
function dumpbr($value, $return = false)
{
    if (!$return)
    {
        var_dump($value);
        echo '<br>' . PHP_EOL;
        return;
    }

    ob_start();
    var_dump($value);
    $result = ob_get_clean() . '<br>' . PHP_EOL;

    return $result;
}


// Check if Laravel's support already loaded this extension
if (!function_exists('dd'))
{

    /**
     * Dump a value and terminate script execution at this point
     *
     * @param mixed $value
     */
    function dd($value)
    {
        dump($value);
        die();
    }

}


function view($path, $data = array(), $value = null)
{
    $view = new View($path, $data, $value);
    return (string)$view;
}