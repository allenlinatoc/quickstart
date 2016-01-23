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
        $path = $dir . (empty($file) ? "" : ("/" . $file));
        if (file_exists($path) && $realpath)
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


if (!function_exists('remove_dir'))
{
    /**
     * Remove a directory
     *
     * @param string $dir       Directory path to be removed
     * @param boolean $rmdir    [optional] Remove this directory as well
     * @return boolean
     */
    function remove_dir($dir, $rmdir = true)
    {
        // Make sure it won't delete root directory by accident
        $dir = trim($dir);
        if (strlen($dir) == 0 || $dir == '/')
        {
            return false;
        }

        if (!is_dir($dir) && !is_file($dir))
        {
            return true;
        }

        $paths = filter_unreal_paths(glob($dir . '/*'));

        foreach ($paths as $path)
        {
            if (is_file($path))
            {
                unlink($path);
                continue;
            }
            if (is_dir($path) && !remove_dir($path))
            {
                return false;
            }
        }

        return $rmdir ? rmdir($dir) : true;
    }
}


if (!function_exists('filter_dir_paths'))
{
    /**
     * Filter out directory paths
     *
     * @param array $paths          An array of paths
     * @param boolean $real_only    [optional] If only real paths must be included
     * @return array
     */
    function filter_dir_paths(array $paths, $real_only = true)
    {
        $result = [ ];

        foreach ($paths as $path)
        {
            $oldpath = $path;
            $path = realpath($path);

            if ($path === false)
            {
                // If path is non-existent, check if real only
                if ($real_only)
                    continue;

                // Otherwise, include in result
                $result[] = $oldpath;
                continue;
            }

            // Skip directories
            if (is_dir($path))
                continue;

            // Otherwise, it's a file, so include it
            $result[] = $path;
        }

        return $result;
    }
}


if (!function_exists('filter_dotted_paths'))
{
    /**
     * Filter dotted paths from an array of path
     *
     * @param array $paths      The array of paths
     * @return array
     */
    function filter_dotted_paths(array $paths)
    {
        $result = [ ];

        foreach ($paths as $path)
        {
            if (regex_test($path, '/(\/|\\\\)?(\.|\.\.)$/'))
                continue;

            $result[] = $path;
        }

        return $result;
    }
}


if (!function_exists('filter_file_paths'))
{
    /**
     * Filter out file paths
     *
     * @param array $paths          An array of paths
     * @param boolean $real_only    [optional] If only real paths must be included
     * @return array
     */
    function filter_file_paths(array $paths, $real_only = true)
    {
        $result = [ ];

        foreach ($paths as $path)
        {
            $oldpath = $path;
            $path = realpath($path);

            if ($path === false)
            {
                // If path is non-existent, check if real only
                if ($real_only)
                    continue;

                // Otherwise, include in result
                $result[] = $oldpath;
                continue;
            }

            // Skip files
            if (is_file($path))
                continue;

            // Otherwise, it's a directory, so include it
            $result[] = $path;
        }

        return $result;
    }
}


if (!function_exists('filter_unreal_paths'))
{
    /**
     * Filter out non-existent paths
     *
     * @param array $paths      Array of paths to be filtered
     * @param boolean $no_dots  [optional] If dotted paths shall be filtered
     * @return array
     */
    function filter_unreal_paths(array $paths, $no_dots = true)
    {
        $result = [ ];

        foreach ($paths as $path)
        {
            $path = realpath($path);

            if ($path === false)
                continue;

            if ($no_dots && regex_test($path, '/(\/|\\\\)?(\.|\.\.)$/'))
                continue;

            $result[] = $path;
        }

        return $result;
    }
}


if (!function_exists('tryresolvepath'))
{
    /**
     * Try to resolve a path
     *
     * @param string $path      The path to be resolved
     * @return string
     */
    function tryresolvepath($path)
    {
        $result = '';

        $path = trim($path);
        if (strlen($path) === 0)
        {
            return $result;
        }

        $path = is_windows($path) ? str_replace('/', SEP, $path) : str_replace('\\', SEP, $path);


        // Explode by current filesystem path separator
        $pathnodes = str_explode(SEP, $path);

        if (sizeof($pathnodes) > 0)
        {
            $result = $pathnodes[0];

            foreach ($pathnodes as $node)
            {
                $result = concat($result, SEP, $node);
                $realpath = realpath($result);

                if (is_string($realpath))
                {
                    $result = $realpath;

                    // If current result is a path to file, there's no point trying to resolve for the next node
                    break;
                }
            }
        }

        return $result;
    }
}