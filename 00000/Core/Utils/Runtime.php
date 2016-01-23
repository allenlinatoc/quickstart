<?php

namespace Utils;

use Utils\String;
use Exceptions\ScalarValueException;

/**
 * Utility class to implement user-defined custom runtime operations
 *
 * @author Allen
 */
class Runtime
{


    /**
     * Convert a runtime value into a virtual runtime value. Returns FALSE if value can't be determined.
     *
     * @param string $value     Any scalar value, EXCEPT if it's an array.
     * @param boolean $throw    [optional] If an exception will be thrown once a virtual runtime value can't be determined. This is useful when determining complex array values.
     * @return string|boolean
     */
    static public function GetVirtual($value, $throw = true)
    {
        if (is_string($value))
        {
            return String::Surround($value, "'");
        }
        else if (is_array($value))
        {
            $closure_for_assoc = function($current) {
                                    $newresult = [];
                                    foreach ($current as $key => $value)
                                    {
                                        array_push($newresult, sprintf("%s => %s", self::GetVirtual($key), $value));
                                        // note that we didn't get Virtual value of $value since this has been previously virualized using array_map()
                                    }

                                    return $newresult;
                                };

            $result = array_map(function($current)
                    {
                        return self::GetVirtual($current);
                    },
                    $value);
            $result = str("[ {0} ]",
                        implode(", ", Arrays::IsIndexed($result) ? $result :
                            // otherwise, return a virtual associative-array result
                            $closure_for_assoc($result)
                        ));

            return $result;
        }
        else if (is_bool($value))
        {
            return $value ? 'true' : 'false';
        }
        else if (is_null($value))
        {
            return "null";
        }
        else if (is_scalar($value))
        {
            return (string)$value;
        }

        if ($throw)
        {
            throw new ScalarValueException($value);
        }

        return false;
    }


    /**
     * Set a runtime variable's value
     *
     * @param string $variable          The variable to be set
     * @param scalar|array $value       The value to be assigned
     * @return null|boolean
     */
    static public function Set($variable, $value)
    {
        $expression = str("$variable = {0};", self::GetVirtual($value));
        return eval($expression);
    }


    /**
     * Get the runtime value of a virtual runtime value
     *
     * @param string $virtual_runtime_value     The virtual runtime value
     * @return mixed
     */
    static public function GetValue($virtual_runtime_value)
    {
        return eval(str("return {0};", $virtual_runtime_value));
    }


}
