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

use \KV;

/**
 * Description of KVTest
 *
 * @author Allen
 */
class KVTest extends \QuickTestCase
{

    private $kvpairs;


    public function __construct()
    {
        $this->kvpairs = [
            'enabled' => true,
            'config1' => md5(time()),
            'config2' => dechex(time()),
            'tester' => 'Allen Linatoc'
        ];
    }


    public function testKey()
    {
        // Single assignment
        $kv = new KV();
        foreach ($this->kvpairs as $key => $value)
        {
            $kv->key($key, $value);
        }
        $this->assertEqualStrict($this->kvpairs, $kv->toArray());


        // Massive assignment
        $kv = new KV();
        $kv->key($this->kvpairs);
        $this->assertEqualStrict($this->kvpairs, $kv->toArray());

        // Get value
        foreach ($this->kvpairs as $key => $value)
        {
            $this->assertEqualStrict($this->kvpairs[$key], $kv->key($key));
        }

        $this->_testKeys();
    }

    public function _testKeys()
    {
        $kv = new KV();
        $kv->key($this->kvpairs);
        $this->assertEqualStrict($kv->keys(), array_keys($this->kvpairs));

        $this->_testKeyEvent();
    }

    public function _testKeyEvent()
    {
        $targetName = 'config1';
        $newvalue = md5(time() * 512);

        $kv = new KV();
        $kv->key($this->kvpairs);
        $kv->keyEvent($targetName, new \Events\Event(function($k, $v) { return preg_replace('/[a-z]+/i', '', $v); }));

        $kv->key($targetName, $newvalue);

        $expect = preg_replace('/[A-Z]+/i', '', $kv->toArray()[$targetName]);
        $this->assertEqualStrict($expect, $kv->key($targetName));

        $this->_testToArray();
    }

    public function _testToArray()
    {
        $kv = new KV();
        $kv->key($this->kvpairs);
        $this->assertEqualStrict($kv->toArray(), $this->kvpairs);

        $this->_testRemove();
    }

    public function _testRemove()
    {
        $kv = new KV();
        $kv->key($this->kvpairs);
        $kv->remove('config1');
        $this->assertEqualStrict($kv->key('config1'), UNDEFINED);

        $this->_testFlushValues();
    }

    public function _testFlushValues()
    {
        $kv = new KV();
        $kv->key($this->kvpairs);
        $kv->flushValues();
        $this->assertEqualStrict(sizeof($kv->toArray()), 0);
    }


}
