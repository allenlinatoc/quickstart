<?php

/*
 * The MIT License
 *
 * Copyright 2016 Allen Linatoc.
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

/**
 * Description of StringTest
 *
 * @author Allen
 */
class StringTest extends \QuickTestCase
{

    private $fences = [
        '{' => '}',
        '[' => ']',
        '<' => '>',
        '(' => ')',
        '\'' => '\'',
        '"' => '"'
    ];


    public function __construct()
    {

    }


    public function testSurround()
    {

        $cases = [
            'normal' => [
                'value' => 'Allen Linatoc',
                'expect' => '%sAllen Linatoc%s'
            ],
            'redundant' => [
                'value' => 'Allen Linatoc"',
                'expect' => '%sAllen Linatoc"%s'
            ],
            'escape' => [
                'value' => 'Allen \'Linatoc',
                'expect' => '%sAllen \\\'Linatoc%s'
            ]
        ];

        foreach ($this->fences as $left => $right)
        {
            foreach ($cases as $casename => $casedata)
            {
                $value = $casedata['value'];
                $expect = sprintf($casedata['expect'], $left, $right);

                $result = \Utils\String::Surround($value, $left, $casename == 'redundant', $casename == 'escape');

                $this->assertEqual($expect, $result, sprintf("%s != %s, casename='$casename', fence='$left$right'", $expect, $result));
            }
        }
    }

}
