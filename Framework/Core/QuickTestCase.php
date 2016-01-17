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

/**
 * An extended SimpleTest unit testing class
 *
 * @author Allen
 */
class QuickTestCase extends UnitTestCase
{

    /**
     * New Unit test case
     *
     * @param string $label Name of test case. Will use the class name if none specified.
     */
    public function __construct($label = false)
    {
        parent::__construct($label);
    }


    /**
     * Assert that given array is empty
     *
     * @param array $array      The array to be tested
     * @param string $message   [optional] The testing error message
     *
     * @return boolean          If this test passed
     */
    public function assertArrayEmpty(array $array, $message = null)
    {
        return $this->assert(
                new EqualExpectation(0),
                sizeof($array),
                !empty($message) ? $message : sprintf("Array is not empty: %s", dump($array, true)));
    }


    /**
     * Assert that given arrays are equal
     *
     * @param array $array1     First array
     * @param array $array2     Second array
     * @param string $message   [optional] The testing error message
     *
     * @return boolean          If this test passed
     */
    public function assertArrayEqual(array $array1, array $array2, $message = null)
    {
        $diff = \Utils\Arrays::Diff($array1, $array2);
        return $this->assert(
                new EqualExpectation(0),
                sizeof($diff),
                !empty($message) ? $message : sprintf("Arrays not equal, difference: %s", dump($diff, true)));
    }


    /**
     * Assert that given values are strictly equal (considering the datatype as well)
     *
     * @param mixed $value1     The first value to be tested
     * @param mixed $value2     The second value to be tested
     * @param string $message   [optional] The testing error message
     *
     * @return boolean          If this test passed
     */
    public function assertEqualStrict($value1, $value2, $message = null)
    {
        return $this->assert(
                new EqualExpectation(true),
                $value1 === $value2,
                !empty($message) ? $message : sprintf("[%s: %s] is not strictly equal to [%s: %s]",
                                    is_scalar($value1) ? $value1 : dump($value1, true), gettype($value1),
                                    is_scalar($value2) ? $value2 : dump($value2, true), gettype($value2)));
    }


    /**
     * Assert that given directory exists
     *
     * @param string $directory The path to directory
     * @param string $message   [optional] The testing error message
     *
     * @return boolean          If this test passed
     */
    public function assertDirectoryExists($directory, $message = null)
    {
        return $this->assert(
                new EqualExpectation(true),
                is_dir($directory),
                !empty($message) ? $message : sprintf("Directory \"%s\" does not exist, or is not a valid directory", $directory));
    }


    /**
     * Assert that given file exists
     *
     * @param string $file      The path to file
     * @param string $message   [optional] The testing error message
     *
     * @return boolean          If this test passed
     */
    public function assertFileExists($file, $message = null)
    {
        return $this->assert(
                new EqualExpectation(true),
                is_file($file),
                !empty($message) ? $message : sprintf("File \"%s\" does not exist, or is not a valid file", $file));
    }


}
