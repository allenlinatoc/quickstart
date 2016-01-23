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

namespace IO;

use Exceptions\NotImplementedException;
use Exceptions\TypeStringException;

/**
 * Class representing a directory
 *
 * @author Allen
 */
class Directory extends AbstractLocalEntity implements IDirectory
{

    public function __construct($path, $create = false, $chmod = UNDEFINED)
    {
        if (!is_string($path))
        {
            throw new TypeStringException($path);
        }

        $this->bindEvents();

        $this->key('path', $path);
        $this->key('chmod', is_empty($chmod) ? 0777 : $chmod);

        if (!$this->exists())
        {
            if ($create)
            {
                $this->create();
            }
        }
        else
        {
            $this->chmod($this->chmod());
        }
    }


    public function children($recursive = false)
    {

    }

    public function copy($destination)
    {

    }

    public function create($recursive = true)
    {
        return mkdir($this->getPath(), $this->chmod(), $recursive);
    }

    public function delete()
    {
        if (!$this->exists())
            return true;

        return rmdir($this->getPath());
    }

    public function deleteEntirely($include_this)
    {
        if (!$this->exists())
            return true;

        remove_dir($this->getPath(), false);
    }

    public function exists()
    {
        return is_dir($this->getPath());
    }

    public function file($filename, $value, $chmod)
    {

    }


    /**
     * Check if this directory is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        if (!$this->exists())
            throw new \Exceptions\IOException(concat("Directory ", $this->getPath(), " not found"));

        return sizeof(filter_dotted_paths(glob($this->getPath() . '/*'))) === 0;
    }


    public function listfiles($recursive = false)
    {
        $result = [ ];

        if ($recursive)
        {
            $recursor = new \RecursiveDirectoryIterator($this->getPath(), \RecursiveDirectoryIterator::SKIP_DOTS);
            $iterator = new \RecursiveIteratorIterator($recursor);

            foreach ($iterator as $key => $value)
            {
                $result[] = (string)$value;
            }
        }
        else
        {
            $result = filter_dir_paths(filter_dotted_paths(glob($this->getPath() . '/*')));
        }

        return $result;
    }


    public function rename($name)
    {
        throw new NotImplementedException(__FUNCTION__);
    }


    public function spawnDirectory($subpath, $create = true)
    {
        // Try to resolve the path
        $subpath = tryresolvepath(concat($this->getPath(), SEP, ltrim($subpath, SEP)));

        if (file_exists($subpath))
        {
            if (is_dir($subpath))
            {
                return new Directory($subpath, $create);
            }

            return UNDEFINED;
        }

        return new Directory($subpath, $create);
    }

}
