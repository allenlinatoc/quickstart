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

namespace Events;

/**
 * A generic event
 *
 * @author Allen
 */
class Event
{

    protected $closure;


    /**
     * Create new event
     *
     * @param Closure $closure
     */
    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
        $this->closure->bindTo($this);
    }


    /**
     * Trigger this event
     *
     * @param mixed $_     Variable number of parameter values to be passed
     * @return mixed
     */
    public function trigger($_ = UNDEFINED)
    {
        if (func_num_args() === 0 ? true : $_ === UNDEFINED)
        {
            return call_user_func($this->closure);
        }

        return call_user_func_array($this->closure, func_get_args());
    }


    /**
     * Trigger this event with array of parameters
     *
     * @param array $params     Array of parameters
     */
    public function triggerArray(array $params)
    {
        return call_user_func_array($this->closure, $params);
    }


    /**
     * Get closure in this instance
     *
     * @return \Closure
     */
    public function getClosure()
    {
        return $this->closure;
    }


    /**
     * Direct invoke
     *
     * @return mixed
     */
    public function __invoke()
    {
        return func_num_args() == 0 ? $this->trigger() : $this->triggerArray(func_get_args());
    }

}
