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

namespace Quickstart\Tests\Utils;

use Utils\Runtime;

/**
 * Description of RuntimeTest
 *
 * @author Allen
 */
class RuntimeTest extends \QuickTestCase
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Should be able to get virtual value of common scalar values
     */
    public function testGetVirtual()
    {
        $values_and_expectations = [

            3 => '3',

            10010 => '10010',

            '0021312321' => "'0021312321'",

            0xff => '255'

        ];

        foreach ($values_and_expectations as $value => $expected)
        {
            $this->assertEqualStrict(Runtime::GetVirtual($value), $expected);
        }
    }

    /**
     * Should be able to get virtual value of arrays
     */
    public function testGetVirtual_Array()
    {
        // Simple array test
        $test = [ 'Hello', 2016, true, 0xff ];
        $expect = "[ 'Hello', 2016, true, 255 ]";
        $result = Runtime::GetVirtual($test);
        $this->assertEqualStrict($result, $expect);

        // Associative array test
        $test = [
            'name' => 'Benjamin Button',
            'age' => 43,
            'hexadecimal_is' => 0xff
        ];
        $expect = "[ 'name' => 'Benjamin Button', 'age' => 43, 'hexadecimal_is' => 255 ]";
        $result = Runtime::GetVirtual($test);
        $this->assertEqualStrict($result, $expect);
    }


    public function testGetVirtual_BooleanTest()
    {
        $this->assertEqual(Runtime::GetVirtual(true), 'true');
        $this->assertEqual(Runtime::GetVirtual(false), 'false');
    }

}
