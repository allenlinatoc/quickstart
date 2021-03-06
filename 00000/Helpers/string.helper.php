<?php


if (!function_exists('str'))
{
    /**
     * String format
     *
     * @param string $string        The string to format
     * @param mixed $_              Formatting parameters
     *
     * @return string
     */
    function str($string)
    {
        $args = func_get_args();
        $size = sizeof($args);

        for ($x = 1; $x < $size; ++$x)
        {
            $arg = $args[$x];
            if (is_array($arg))
            {
                $string = call_user_func_array('str', $arg);
                continue;
            }

            $string = str_replace(sprintf("{%s}", $x - 1), $arg, $string);
        }

        return $string;
    }
}


if (!function_exists('concat'))
{
    /**
     * Concatenate two or more strings
     *
     * @param string $str1  First string to be concatenated
     * @param string $str2  Second string to be concatenated
     * @param string $_     [optional] Variable number of strings to be concatenated
     * @return string
     */
    function concat($str1, $str2, $_ = null)
    {
        $result = "";
        $func_args = func_get_args();
        $func_count = sizeof($func_args);

        for ($x = 0; $x < $func_count; $x++)
        {
            $result .= $func_args[$x];
        }

        return $result;
    }
}


if (!function_exists('println'))
{
    /**
     * Print string with PHP_EOL at the end
     *
     * @param string $str   The string to be printed
     */
    function println($str)
    {
        echo implode('', func_get_args()), PHP_EOL;
    }
}


if (!function_exists('printbr'))
{
    /**
     * Print string with HTML line break
     *
     * @param string $str       The string to be printed
     * @param boolean $newline  [optional] If PHP_EOL should be appended
     */
    function printbr($str, $newline = false)
    {
        $last = func_get_arg(func_num_args() - 1);
        $nl = is_bool($last) ? $last : false;
        echo $str, "<br>", $newline ? PHP_EOL : '';
    }
}


if (!function_exists('regex_test'))
{
    /**
     * Check if a string matches a regex
     *
     * @param string $string    The string to be tested
     * @param string $regex     The regular expression
     * @return boolean
     */
    function regex_test($string, $regex)
    {
        return !in_array(preg_match($regex, $string), [ 0, false ]);
    }
}


if (!function_exists('str_explode'))
{
    /**
     * Explode a string into array, with white-space trimmed, and empty strings exluded
     *
     * @param string $delimiter     The delimiter
     * @param string $str           The string to be exploded
     */
    function str_explode($delimiter, $str)
    {
        $explode = explode($delimiter, $str);

        $result = [ ];

        foreach ($explode as $string)
        {
            $string = trim($string);

            if (strlen($string) === 0)
                continue;

            $result[] = $string;
        }

        return $result;
    }
}