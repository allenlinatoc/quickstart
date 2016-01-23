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

namespace Utils;


use Zend\Json\Json as ZendJson;

/**
 * JSON utility class, with some functions built on top of Zend\Json\Json
 *
 * @author Allen
 */
class JSON
{

    const DECODE_ARRAY = 1;
    const DECODE_OBJECT = 0;


    /**
     * Encode any mixed-type value into JSON
     *
     * @param mixed $value          Value to be encoded to JSON
     * @param boolean $prettify     [optional] If JSON should be prettify-formatted
     * @param boolean $html         [optional] If spaces/indents should be properly viewable for HTML
     * @return string
     */
    static public function Encode($value, $prettify = false, $html = false)
    {
        $json = ZendJson::encode($value);
        if ($prettify)
        {
            $json = ZendJson::prettyPrint($json);
            if ($html)
            {
                $json = str_replace("\n", '<br>', str_replace(' ', '&nbsp;', $json));
            }
        }

        return $json;
    }


    /**
     * Decode
     *
     * @param string $json      
     * @param int $decodeType
     */
    static public function Decode($json, $decodeType = self::DECODE_ARRAY)
    {
        return ZendJson::decode($json, $decodeType);
    }

}
