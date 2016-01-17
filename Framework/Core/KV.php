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

use Exceptions\ScalarValueException;

/**
 * A key-value store class
 *
 * @author Allen
 */
class KV extends \QuickClass
{

    protected $kvpairs;
    protected $events;


    /**
     * New Configurable instance
     */
    public function __construct()
    {
        parent::__construct([]);
        $this->kvpairs = [];
        $this->events = [];
    }


    /**
     * Flush key-value hive of this instance
     */
    public function flushValues()
    {
        $this->kvpairs = [];
    }


    /**
     * Get or set a key's value
     *
     * @param string|array $name    The name of target key, array of assignments
     * @param mixed $value          [optional] The new value to be set, if specified.
     *
     * @return mixed
     */
    public function key($name, $value = UNDEFINED)
    {
        if (is_array($name))
        {
            foreach ($name as $key => $k_value)
            {
                $this->key($key, $k_value);
            }
            return $this->kvpairs;
        }

        self::_ValidateName($name);

        if ($value === UNDEFINED)
        {
            return isset($this->kvpairs[$name]) ? $this->kvpairs[$name] : UNDEFINED;
        }

        // Check if there's an event for this key
        if (isset($this->events[$name]))
        {
            $event = $this->events[$name];
            $value = $event($name, $value); // execute trigger event and get value
        }
        $this->kvpairs[$name] = $value;
        return $this->kvpairs[$name];
    }


    /**
     * Check if a key is defined
     *
     * @param string $name      Name of key to be checked
     * @return boolean
     */
    public function keyDefined($name)
    {
        return isset($this->kvpairs[$name]);
    }


    /**
     * Return an array of keys
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->kvpairs);
    }


    /**
     * Register an event for a key. This event
     *
     * @param string|array $name    The name/array of names of target key
     * @param \Events\Even $event          [optional] function($key, $value) - The event to be assigned with parameters
     * @return \Events\Even|array|null
     */
    public function keyEvent($name, \Events\Event $event = null)
    {
        // Massive setup
        if (is_array($name))
        {
            if (is_null($event))
            {
                $result = [];

                foreach ($name as $_name)
                {
                    $result[$_name] = $this->events[$_name];
                }

                return $result;
            }

            foreach ($name as $_name)
            {
                $this->keyEvent($_name, $event);
            }
        }


        // Individual setup

        if (is_null($event))
        {
            return isset($this->events[$name]) ? $this->events[$name] : UNDEFINED;
        }

        // Proceed setting events

        if (!isset($this->events[$name]))
        {
            $this->events[$name] = $event;
        }

        return;
    }


    /**
     * Get the associative array of key-value pairs
     *
     * @return type
     */
    public function toArray()
    {
        return $this->kvpairs;
    }


    /**
     * Remove a key
     *
     * @param string $name Name of key to remove
     */
    public function remove($name)
    {
        self::_ValidateName($name, true);

        if (isset($this->kvpairs[$name]))
        {
            unset($this->kvpairs[$name]);
        }
    }


    /**
     * Validate a key name
     *
     * @param string $name      The name to be validated
     * @param boolean $throw    [optional] If an exception shall be thrown when validation failed
     *
     * @return boolean
     *
     * @throws ScalarValueException
     */
    static private function _ValidateName($name, $throw = true)
    {
        if (!is_scalar($name))
        {
            if ($throw)
            {
                throw new ScalarValueException($name);
            }

            return false;
        }

        return true;
    }


}
