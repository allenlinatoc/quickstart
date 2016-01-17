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

namespace IO;

use Exceptions\IOException;

/**
 * Description of Directory
 *
 * @author Allen
 */
class Directory extends AbstractLocalEntity implements \IO\IDirectory
{


    public function __construct($path, $create = false, $chmod = null)
    {
        $this->path = localpath($path);
        $this->key('create', $create);
        $this->key('chmod', $chmod);

        if ($this->key('create'))
        {
            if (!$this->create())
            {
                throw new IOException(str("Failed to create directory \"{0}\".", $this->getPath()));
            }
        }

        if ($this->chmodDefined() && $this->exists())
        {
            $this->chmod($chmod);
        }
    }


    public function copy($destination)
    {
        
    }


    /**
     * Create this directory. Returns boolean if this directory has been successfully created.
     *
     * @return boolean
     */
    public function create()
    {
        if (!$this->exists())
        {
            $result = mkdir($this->getPath(), octdec($this->key('chmod')));
            if ($this->chmodDefined())
            {
                if (!$this->chmod($this->key('chmod')))
                {
                    throw new IOException(str("Failed to set CHMOD of file \"{0}\" to {1}", $this->getPath(), $this->key('chmod')));
                }
            }

            return $result;
        }

        return true;
    }


    /**
     * Create this directory recursively. Returns boolean if this directory has been successfully created.
     *
     * @return boolean
     */
    public function createRecursively()
    {
        if (!$this->exists())
        {
            $result = mkdir($this->getPath(), octdec($this->key('chmod')), true);
            if ($this->chmodDefined())
            {
                if (!$this->chmod($this->key('chmod')))
                {
                    throw new IOException(str("Failed to set CHMOD of file \"{0}\" to {1}", $this->getPath(), $this->key('chmod')));
                }
            }

            return $result;
        }

        return true;
    }


    /**
     * Delete this directory. Returns boolean if this directory has been successfully deleted.
     *
     * @return boolean
     */
    public function delete()
    {
        return rmdir($this->getPath());
    }


    public function deleteEntirely()
    {
        $files = $this->listfiles(true);

    }


    /**
     * Rename this directory.
     *
     * @param string $name      Name of new directory
     */
    public function rename($name)
    {
        $newpath = concat($this->parent()->getPath(), "/", $name);
        $result = rename($this->getPath(), $newpath);

        if ($result)
        {
            $this->path = $newpath;
        }

        return $result;
    }


    /**
     * List files in this directory, returns an array of paths
     *
     * @param boolean $recursive    [optional] If true, then child directories will be recursed as well
     * @return array
     */
    public function listfiles($recursive = false)
    {
        $thisIterator = $recursive ?
                new \RecursiveDirectoryIterator($this->getPath(), \RecursiveDirectoryIterator::SKIP_DOTS) :
                new \DirectoryIterator($this->getPath());

        $result = \Utils\Arrays::FromTraversable($thisIterator);

        if (!$recursive)
        {
            $result = \Utils\Arrays::Filter($result, [ '..', '.' ]);
        }

        // resolve to canonicalized absolute path
        array_walk($result,
                function(&$value, $key, $path) {

                    $value = localpath($path, $value);

                },
                $path = $this->getPath());

        return $result;
    }


    /**
     * Create/Retrieve a file from this directory
     *
     * @param string $filename          File name
     * @param boolean $create           [optional] If file should be created if it does not exist
     * @param int $chmod                [optional] CHMOD to be applied
     * @return \IO\File
     */
    public function file($filename, $create = false, $chmod = null)
    {
        $path = localpath($this->getPath(), $filename);
        return self::Prepare($path, $create, $chmod);
    }


    /**
     * Derive another directory instance based on relative path supplied
     *
     * @param string $relative    The relative path to derive from, you can also use symbolic link and dot expression as well
     */
    public function derive($relative, $create = false, $chmod = null)
    {
        $newpath = concat($this->getPath(), "/", ltrim($relative, "/\\"));
        if (file_exists($newpath))
        {
            if (is_file($newpath))
            {
                return false;
            }
            $newpath = realpath($newpath);
        }

        $result = new \IO\Directory($newpath);

        return $result;
    }

    /**
     * Get an array of child \IO\File objects. TODO: Unit test
     *
     * @param boolean $recursive    [optional] If true, then child directories will be recursed as well
     * @return array
     */
    public function children($recursive = false)
    {
        $result = [];

        $files = $this->listfiles($recursive);

        foreach ($files as $path)
        {
            array_push($result, new \IO\File($path));
        }

        return $result;
    }

}