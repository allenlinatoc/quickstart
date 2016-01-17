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

use Exceptions\FileNotFoundException;

/**
 * Class representing a local file
 *
 * @author Allen
 */
class File extends AbstractLocalEntity implements \IO\IFile
{


    /**
     * New File instance
     *
     * @param string $path          The path to file
     * @param boolean $create       [optional] If file should be created if it does not exist
     * @param int $chmod            [optional] CHMOD to be applied
     */
    public function __construct($path, $create = false, $chmod = null)
    {
        $this->path = $path;
        $this->key('create', $create);
        $this->key('chmod', $chmod);

        if ($this->key('create'))
        {
            $this->create();
        }

        if ($this->chmodDefined() && $this->exists())
        {
            $this->chmod($this->key('chmod'));
        }
    }


    /**
     * Copy this file. Returns FALSE if not
     *
     * @param \IO\AbstractLocalEntity|string $destination      Destination path or entity
     * @return \IO\File|boolean
     */
    public function copy($destination)
    {
        $this->throwIfNotFound();

        $path = is_string($destination) ? $destination : $destination->getPath();

        if (copy($this->getPath(), $path))
        {
            return new File($dest, false, $this->chmod);
        }

        return false;
    }


    /**
     * Try to create this file if it does not exist
     *
     * @return boolean
     */
    public function create()
    {
        if (!$this->exists())
        {
            // Check first if parent directory exists
            $dir = $this->getPathInfo()->getDirname();
            if (!file_exists($dir))
            {
                $dir = new Directory($dir, true);
            }
            return $this->write('');
        }

        return true;
    }


    /**
     * Delete this file
     *
     * @return boolean
     */
    public function delete()
    {
        if (!$this->exists())
        {
            return true;
        }

        return unlink($this->getPath());
    }


    /**
     * Read contents of this file
     *
     * @return string
     */
    public function read()
    {
        $this->throwIfNotFound();

        $content = file_get_contents($this->getPath());
        return $content;
    }


    public function rename($name)
    {
        rename($this->getPath(), $newname);
    }

    public function sha1()
    {
        $this->throwIfNotFound();

        return sha1_file($this->getPath());
    }

    public function truncate()
    {
        $this->throwIfNotFound();

        $this->write('');
    }

    public function write($content)
    {
        return file_put_contents($this->getPath(), $content);
    }

    protected function throwIfNotFound()
    {
        if (!$this->exists())
        {
            throw new FileNotFoundException($this->getPath());
        }
    }

}
