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

namespace Quickstart\Tests;

use \Events\Event as Event;

/**
 * Description of EventTest
 *
 * @author Allen
 */
class EventTest extends \QuickTestCase
{

    /** @var Closure */
    private $closure;

    /** @var Closure */
    private $closureWithScope;

    /** @var Closure */
    private $closureWithScopeSetting;

    private $property = null;


    public function __construct()
    {
        $this->closure = function($name, $age)
        {
            return implode('=>', [ $name, $age ]);
        };

        $this->closureWithScope = function($content)
        {
            $this->printContent($content);
        };

        $this->closureWithScopeSetting = function($value)
        {
            $this->property = $value;
        };
    }


    /**
     * A print test method
     *
     * @param string $content   The content to be printed
     */
    public function printContent($content)
    {
        echo $content;
    }


    public function testConstructor_ClosureScope()
    {
        $expect = 'Hello world!';

        $printValueEvent = new Event($this->closureWithScope);
        ob_start();
        $printValueEvent->trigger($expect);
        $output = ob_get_clean();

        $this->assertEqual($expect, $output);

        $expect = [ 'one', 'two', 'three' ];
        $setValueEvent = new Event($this->closureWithScopeSetting);
        $setValueEvent->trigger($expect);
        $this->assertEqual($expect, $this->property);
    }


    public function testTrigger()
    {
        // Test with no parameter
        $expect = 'Ok';
        $event = new Event(function() { return 'Ok'; });
        $output = $event->trigger();

        $this->assertEqual($expect, $output);

        // Test with parameters
        $params = [ 'Allen', 21 ];
        $expect = implode('=>', $params);

        $event = new Event($this->closure);
        $eventOutput = $event->trigger($params[0], $params[1]);

        $this->assertEqual($expect, $eventOutput);
    }


    public function testTriggerArray()
    {
        $params = [ 'Allen', 21 ];
        $expect = implode('=>', $params);

        $event = new Event($this->closure);
        $eventOutput = $event->triggerArray($params);

        $this->assertEqual($expect, $eventOutput);
    }


    public function test__invoke()
    {
        // Test with no parameter
        $expect = 'Ok';
        $event = new Event(function() { return 'Ok'; });
        $output = $event();

        $this->assertEqual($expect, $output);

        // Test with parameters
        $params = [ 'Allen', 21 ];
        $expect = implode('=>', $params);

        $event = new Event($this->closure);
        $eventOutput = $event($params[0], $params[1]);

        $this->assertEqual($expect, $eventOutput);
    }


}