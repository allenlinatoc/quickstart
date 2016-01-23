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

use Utils\Arrays;

/**
 * Description of ArraysTest
 *
 * @author Allen
 */
class ArraysTest extends \QuickTestCase
{

    private $array = [
        'name' => 'Allen Linatoc',
        'age' => 21,
        'address' => [
            'city' => 'Calamba',
            'province' => 'Laguna',
            'country' => 'Philippines',
            'coordinates' => [
                'latitude' => 121.00212,
                'longitude' => 14.1241
            ]
        ]
    ];


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Should be able to properly identify the difference between different arrays
     */
    public function testDiff()
    {
        $array1 = [ 1, 2, 9, 3, 5, 6, 8, 'string' ];
        $array2 = [ 1, 2, 4, 5, 7, 8 ];
        $expect = [ 9, 3, 6, 'string', 4, 7 ];
        $result = Arrays::Diff($array1, $array2);
        $this->assertEqual($expect, $result);
    }

    /**
     * Should be able to tell same arrays
     */
    public function testDiff_NoDifference()
    {
        $array1 = [ 1, 2, 9, 3, 5, 6, 8, 'string' ];
        $array2 = [ 1, 2, 9, 3, 5, 6, 8, 'string' ];
        $expect = [ ];
        $result = Arrays::Diff($array1, $array2);
        $this->assertEqual($expect, $result);
    }

    /**
     * Should be able to tell if an array is purely indexed or associative
     */
    public function testIsIndexed()
    {
        $test = explode(' ', 'Hello there Mister Donut!');
        $this->assertTrue(Arrays::IsIndexed($test));

        $test = [ 0 => 'Hello', 1 => 'There!', 2 => 'My Friend!' ];
        unset($test[1]);
        $this->assertTrue(Arrays::IsIndexed($test));

        $test = [ 0 => 'Hello', 2 => 'My Friend!', 1 => 'There!' ];
        $this->assertFalse(Arrays::IsIndexed($test));

        $test = [ 0 => 'Hello', true => 'There!' ];
        $this->assertTrue(Arrays::IsIndexed($test)); // there's no remedy to determine if a key is int(1) or just boolean(1|true)

        $test = [ 0 => 'Hello', 'name' => 'Allen' ];
        $this->assertFalse(Arrays::IsIndexed($test));
    }

    /**
     * Should be able to set simple values in a simple dimensions
     */
    public function testRecursiveSet_Simple()
    {
        $result = $this->array;

        $expected = 30;
        $result = Arrays::RecursiveSet($result, '{age}', $expected);
        $this->assertEqual($result['age'], $expected);

        $expected = 'Allen Linatoc';
        $result = Arrays::RecursiveSet($result, '{name}', $expected);
        $this->assertEqual($result['name'], $expected);
    }

    /**
     * Should be able to set simple values in 1 level deeper dimension
     */
    public function testRecursiveSet_Deep()
    {
        $result = $this->array;

        $expected = 'Sto. Tomas';
        $result = Arrays::RecursiveSet($result, '{address}{city}', $expected);
        $this->assertEqual($result['address']['city'], $expected);

        $expected = 'Batangas';
        $result = Arrays::RecursiveSet($result, '{address}{province}', $expected);
        $this->assertEqual($result['address']['province'], $expected);
    }

    public function testRecursiveSet_DeepExpectsCompoundResult()
    {
        $result = $this->array;

        $new_value = [
            'latitude' => 121.79011,
            'longitude' => 14.55001
        ];
        $result = Arrays::RecursiveSet($result, '{address}{coordinates}', $new_value);
        $this->assertEqual($result['address']['coordinates'], $new_value);
    }

    /**
     * Should receive a simple value
     */
    public function testTraverse_Simple()
    {
        $result = Arrays::Traverse($this->array, "{name}");
        $this->assertEqual($result, $this->array['name']);
    }

    /**
     * Should receive a simple value, but 1 level deeper dimension
     */
    public function testTraverse_Deep()
    {
        $result = Arrays::Traverse($this->array, "{address}{city}");
        $this->assertEqual($result, $this->array['address']['city']);
    }

    /**
     * Should receive an array within 1 level deeper dimension
     */
    public function testTraverse_DeepExpectsCompoundResult()
    {
        $result = Arrays::Traverse($this->array, "{address}{coordinates}");
        $this->assertEqual($result, $this->array['address']['coordinates']);
    }

    /**
     * Should receive values of different datatype within 2 level deeper dimension
     */
    public function testTraverse_Deep2AndDatatypeSensitive()
    {
        $result = Arrays::Traverse($this->array, "{address}{coordinates}{latitude}");
        $expected = $this->array['address']['coordinates']['latitude'];
        $this->assertEqual($result, $expected);

        // must be a float
        $this->assertIsA($result, 'float');

        $result = Arrays::Traverse($this->array, "{address}{coordinates}");
        $this->assertIsA($result, 'array');
    }

    /**
     * Should receive dimension nodes from a string
     */
    public function testGetDimensionNodes()
    {
        $test = "{allen}{linatoc}{2015}";
        $expect = [ 'allen', 'linatoc', '2015'];

        $this->assertEqualStrict(Arrays::GetDimensionNodes($test), $expect);
    }

    /**
     * Should receive values of an array
     */
    public function testGetValues()
    {
        $test = $this->array;
        $expect = array_values($test);
        $result = Arrays::GetValues($test);
        $this->assertEqualStrict($expect, $result);
    }

    /**
     * Should receive values of an array, recursively
     */
    public function testGetValues_Recursive()
    {
        $test = $this->array;
        $expect = [ 'Allen Linatoc', 21, 'Calamba', 'Laguna', 'Philippines', 121.00212, 14.1241 ];
        $result = Arrays::GetValues($test, true);
        $this->assertArrayEqual($expect, $result);
    }

    public function testFromTraversable()
    {

        $arrayIterator = new \RecursiveArrayIterator($this->array);
        $it = new \RecursiveIteratorIterator($arrayIterator);
    }

    /**
     * Should be able to do simple array filtering
     */
    public function testFilter()
    {
        $test = $this->array;
        $filter = [ 'Allen Linatoc', 21 ];
        $expect = [
                'address' => [
                    'city' => 'Calamba',
                    'province' => 'Laguna',
                    'country' => 'Philippines',
                    'coordinates' => [
                        'latitude' => 121.00212,
                        'longitude' => 14.1241
                    ]
                ]
            ];
        $result = Arrays::Filter($test, $filter);
        $this->assertArrayEqual($expect, $result);
    }

    public function testFilter_Recursive()
    {
        $test = $this->array;
        $filter = [ 'Allen Linatoc', 21, 'Laguna', 14.1241 ];
        $expect = [
                'address' => [
                    'city' => 'Calamba',
                    'country' => 'Philippines',
                    'coordinates' => [
                        'latitude' => 121.00212
                    ]
                ]
            ];
        $result = Arrays::Filter($test, $filter, true);
        $this->assertArrayEqual($expect, $result);
    }



}
