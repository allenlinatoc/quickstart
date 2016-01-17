<?php

/*
 * The MIT License
 *
 * Copyright 2015 Allen Linatoc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Utils;

use Utils\String;
use Utils\JSON;

/**
 * Utility class for simple and complex arrays
 *
 * @author Allen
 */
class Arrays
{

    /**
     * Tell the difference between two arrays. NOTE that the order of result comes by values found from array1, array2,... and so forth. Though each of the arrays are sorted first using SORT_STRING (see PHP manual) implementation.
     *
     * @param array $array1     First array to be compared
     * @param array $array2     Second array to be compared
     * @param array $_          Variable number of arrays to be compared
     * @return array
     */
    static public function Diff(array $array1, array $array2, $_ = null)
    {
        $args = func_get_args();
        $diff = [];

        // remove duplicates from supplied arrays
        // will help reduce search volume during diff
//        for ($x = 0, $size = count($args); $x < $size; $x++)
//        {
//            $args[$x] = array_unique($args[$x]);
//        }

        for ($x = 0, $size = sizeof($args); $x < $size; $x++)
        {
            $current_array = $args[$x];
            foreach ($current_array as $value)
            {
                // Check if this current value already exists in found "diff" values
                if (in_array($value, $diff))
                {
                    // next value please...
                    continue;
                }

                for ($y = 0; $y < $size; $y++)
                {
                    $current_array_search = $args[$y];

                    // We don't diff an array to its own, so skip
                    if ($x == $y)
                    {
                        continue;
                    }

                    if (!in_array($value, $current_array_search))
                    {
                        array_push($diff, $value);
                        break;
                    }
                }
            }
        }

        return $diff;
    }

    /**
     * Checks if an array is purely indexed with numeric indices. Broken indices are still considered, though it can't tell whether an index is just boolean (1|0)
     *
     * @param array $array  The array specimen
     * @return boolean
     */
    static public function IsIndexed(array $array)
    {
        $keys = array_keys($array);
        $lastkey = null;

        foreach ($keys as $key)
        {
            if (!is_int($key))
            {
                return false;
            }

            if ($lastkey === null)
            {
                $lastkey = $key;
                continue;
            }

            if ($key < $lastkey)
            {
                return false;
            }

            $lastkey = $key;
        }

        return true;
    }


    /**
     * Traverse a multi-dimensional array. Returns UNDEFINED if not found.
     *
     * @param array $array          The target array to be searched
     * @param string $dimension     The array dimension, in format of {level1}{level2}{levelX}, etc.
     * @return mixed
     */
    static public function Traverse(array $array, $dimension)
    {
        $nodes = self::GetDimensionNodes($dimension);

        $current = $array;
        foreach ($nodes as $node)
        {
            if (!isset($current[$node]))
            {
                return UNDEFINED;
            }

            $current = $current[$node];
        }

        return $current;
    }


    /**
     * Recursively set value of a dimension in a multi-dimensional array. Returns the resulting processed array.
     *
     * @param array $array              Target array
     * @param string|array $dimension   String, as an array dimension, in format of {level1}{level2}{levelX}, etc.; or Array, as an associative array of multilple assignments ( 'dimension1' => 'value1', 'dimension2' => 'value2' )
     * @param scalar|array $value       Any scalar or array value to be assigned, for single assignment
     * @param boolean $autolink         [optional] If missing nodes should be auto-linked, otherwise, will throw an exception
     * @return array
     *
     * @throws \Exceptions\DimensionNotFoundException
     */
    static public function RecursiveSet(array $array, $dimension, $value, $autolink = false)
    {
        // If this is a multiple assignment
        if (is_array($dimension))
        {
            $result = $array;

            $keys = array_keys($dimension);
            foreach ($keys as $key)
            {
                $result = self::RecursiveSet($result, $key, $dimension[$key], $autolink);
            }

            return $result;
        }

        $nodes = self::GetDimensionNodes($dimension);
        $final_node = $nodes[sizeof($nodes) - 1];

        $current = $array;
        $variable = '$current';

        foreach ($nodes as $current_node)
        {
            $variable .= "['$current_node']";

            if (!eval('return isset($variable);'))
            {

                if (!$autolink)
                {
                    throw new \Exceptions\DimensionNotFoundException($array, $dimension);
                }

                // Link node if `autolink` is true
                eval("$variable = array();");
            }

            // If this is the final node, set it, NOW
            if ($final_node == $current_node)
            {
                eval(str("$variable = {0};", Runtime::GetVirtual($value)));
            }

        }

        return eval(str("return {0};", substr("$variable", 0, strpos("$variable", "["))));

    }


    /**
     * Create array from a traversable object
     *
     * @param \Traversable $traversable     Traversable object to derive from
     * @return array
     */
    static public function FromTraversable(\Traversable $traversable)
    {
        $result = [ ];
        foreach ($traversable as $value)
        {
            array_push($result, (string)$value);
        }

        return $result;
    }


    /**
     * Create array from an iterator object
     *
     * @param \Iterator $iterator
     * @param type $recursive
     * @param type $cast_to
     */
    static public function FromIterator(\Iterator $iterator, $recursive = false, $cast_to = null)
    {
        $result = [ ];

        $iterator = $recursive ? new \RecursiveIteratorIterator($iterator) : new \IteratorIterator($iterator);
        foreach ($iterator as $value)
        {
            if ($cast_to === null)
            {
                array_push($result, $value);
                continue;
            }

            $newvalue = UNDEFINED;
            switch ($cast_to)
            {
                case 'string':
                    $newvalue = (string)$value;
                    break;
                case 'int':
                    $newvalue = intval($value);
                    break;
                case 'float':
                    $newvalue = floatval($value);
                    break;
                case 'bool':
                    $newvalue = boolval($value);
                    break;
                default:
                    $newvalue = $value;
            }

            array_push($result, $newvalue);
        }

        // TODO: More stuff to do
        return $result;
    }


    /**
     * Get the array of nodes of an array dimension
     *
     * @param string $dimension
     * @return array
     */
    static public function GetDimensionNodes($dimension)
    {
        // Get nodes
        $matches = null;
        preg_match_all('|\{(.+)\}|U', $dimension, $matches, PREG_PATTERN_ORDER);

        return isset($matches[1]) ? $matches[1] : [];
    }


    /**
     * Get child values of an array
     *
     * @param array $array          The array specimen
     * @param boolean $recursive   [optional] If values of child arrays will also be included, default is FALSE
     * @return array
     */
    static public function GetValues(array $array, $recursive = false)
    {
        $result = [];

        foreach ($array as $value)
        {
            if (is_array($value) && $recursive)
            {
                $result = array_merge($result, self::GetValues($value, $recursive));
                continue;
            }

            array_push($result, $value);
        }

        return $result;
    }


    /**
     * Filter out values from an array based on a filter
     *
     * @param array $array          The array to be filtered out
     * @param mixed $filter         A value or array of values to be filtered out from specified array
     * @param boolean $recursive    [optional] If filtering should be recursive as well
     * @return array
     */
    static public function Filter($array, $filter, $recursive = false)
    {
        $filter = !is_array($filter) ? [ $filter ] : $filter;

        $array = $array instanceof \Traversable ? self::FromTraversable($array) : $array;

        foreach ($array as $key => $value)
        {
            // Check for recursive ops
            if ($recursive && is_array($value))
            {
                $array[$key] = self::Filter($array[$key], $filter, $recursive);
                continue;
            }

            if (in_array($value, $filter, true))
            {
                unset($array[$key]);
            }
        }

        return $array;
    }


}
