<?php

namespace Utils;

/**
 * Utlity class for advance string manipulation
 *
 * @author Allen
 */
class String
{

    static private $_Fences = [
        '{' => '}',
        '[' => ']',
        '<' => '>',
        '(' => ')'
    ];



    /**
     * Surround a string with certain character/s
     *
     * @param string $str           The string value to be surrounded
     * @param string $fence         [optional] The fence to be used. Pair characters are also detected as well.
     * @param boolean $redundant    [optional] If redundant fence characters shall be removed first, default is FALSE
     * @param boolean $escape       [optional] If quotes shall be escaped, default is TRUE
     * @return string
     */
    static public function Surround($str, $fence = '"', $redundant = false, $escape = true)
    {
        $str = $escape ? addslashes($str) : $str;

        $isUnique = isset(self::$_Fences[$fence]);

        if ($isUnique)
        {
            $thisFence = [ $fence, self::$_Fences[$fence] ];
            if (!$redundant)
            {
                $str = rtrim(ltrim($str, $thisFence[0]), $thisFence[1]);
            }
            $str = str("{0}{1}{2}", $thisFence[0], $str, $thisFence[1]);
        }
        else
        {
            if (!$redundant)
            {
                $str = trim($str, $fence);
            }

            $str = str("{0}{1}{0}", $fence, $str);
        }

        return $str;
    }


    /**
     * Surround string values inside an array. Non-string values will be left as it is.
     *
     * @param string $array         The array specimen
     * @param string $fence         [optional] The fence to be used. Pair characters are also detected as well.
     * @param boolean $redundant    [optional] If redundant fence characters shall be removed first, default is FALSE
     * @param boolean $escape       [optional] If quotes shall be escaped, default is TRUE
     * @param boolean $recursive    [optional] If true, then child arrays will be processed as well, default is FALSE
     * @return string
     */
    static public function SurroundArray(array $array, $fence = '"', $redundant = false, $escape = true, $recursive = false)
    {
        $result = [];
        foreach ($array as $key => $value)
        {
            if (!is_string($value))
            {
                continue;
            }
            else if (is_array($value) && $recursive)
            {
                $result[$key] = self::SurroundArray($value, $fence, $redundant, $escape, $recursive);
            }
            else
            {
                $result[$key] = self::Surround($value, $fence, $redundant, $escape);
            }
        }

        return $result;
    }


    /**
     * Let's say explode() and str_split() is combined in this function
     *
     * @param string $str                           [optional] String specimen
     * @param string|int $delimiter_or_chunksize    [optional] Delimiting value
     * @return array
     *
     * @throws \Exceptions\NullArgumentException
     */
    static public function Split($str, $delimiter_or_chunksize = 1)
    {
        if (is_null($delimiter_or_chunksize))
        {
            throw new \Exceptions\NullArgumentException('delimiter_or_chunksize');
        }

        if (is_int($delimiter_or_chunksize))
        {
            return str_split($str, $delimiter_or_chunksize);
        }
        else
        {
            return explode($delimiter_or_chunksize, $str);
        }
    }


}
