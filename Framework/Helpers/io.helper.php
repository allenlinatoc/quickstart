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



if (!function_exists('localpath'))
{
    /**
     * Get local path (canonicalized absolute path, if it exists), with formatting based on current OS
     *
     * @param string $dir       The path to directory
     * @param string $file      [optional] The file/filename, if trying to get path to file
     * @param string $realpath  [optional] If path should be resolved into a canonicalized absolute path, only applies when path exists. Default is TRUE
     * @return string
     */
    function localpath($dir, $file = '', $realpath = true)
    {
        $path = $dir . (!empty($file) ? "/" . $file : "");
        if (file_exists($path) && $realpath)
        {
            $path = realpath($path);
        }

        $path = is_windows() ? str_replace("/", "\\", $path) : str_replace("\\", "/", $path);
        
        return $path;
    }


    /**
     * Get local file path (canonicalized absolute path, if it exists), with formatting based on current OS
     *
     * @param string $file      Path to file
     * @param boolean $realpath [optional] If path should be resolved into a canonicalized absolute path, only applies when path exists. Default is TRUE
     * @return string
     */
    function localfile($file, $realpath = true)
    {
        $path = $file;

        if (file_exists($file) && $realpath)
        {
            $path = realpath($path);
        }

        $path = is_windows() ? str_replace("/", "\\", $path) : str_replace("\\", "/", $path);

        return $path;
    }
}


if (!function_exists('listfiles'))
{
    /**
     * List files under a path
     *
     * @param string $path          Path which files will be enlisted
     * @param boolean $recursive    [optional] If listing shall be recursive. Default is FALSE
     * @return array
     */
    function listfiles($path, $recursive = false)
    {
        $iterator = $recursive ?
                new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS) :
                new DirectoryIterator($path);

        $array = \Utils\Arrays::FromTraversable($iterator);

        if (!$recursive)
        {
            $array = \Utils\Arrays::Filter($array, [ '.', '..' ]);
        }

        return $array;
    }
}