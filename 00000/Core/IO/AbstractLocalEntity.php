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

use IO\PathInfo;


/**
 * An adapter for a local IO entity
 *
 * @author Allen
 */
abstract class AbstractLocalEntity extends \KV implements IEntity
{

    protected $path = null;
    protected $eventsBinded = false;


    /**
     * Check if this entity exists
     *
     * @return boolean
     */
    public function exists()
    {
        return file_exists($this->getPath());
    }


    /**
     * Get filesystem path of this instance
     *
     * @return string
     */
    public function getPath()
    {
        $path = $this->key('path');
        return file_exists($this->key('path')) ? realpath($this->key('path')) : $this->key('path');
    }


    /**
     * Get path info of this entity's path
     *
     * @return \IO\PathInfo
     */
    public function getPathInfo()
    {
        return new PathInfo($this->getPath());
    }


    /**
     * Get name (directory basename or filename) of this local entity
     *
     * @return string
     */
    public function getName()
    {
        return $this->getPathInfo()->getBasename();
    }


    /**
     * Check if CHMOD is defined for this entity
     *
     * @return boolean
     */
    public function chmodDefined()
    {
        return $this->keyDefined('chmod') && !is_empty( $this->key('chmod') );
    }


    /**
     * Get or set chmod of this entity. Returns INT on get, or FALSE if no CHMOD currently defined. Returns BOOLEAN on set, or FALSE on failure.
     *
     * @param string|int $chmod        [optional] New chmod to be set
     * @return string|boolean
     */
    public function chmod($chmod = UNDEFINED)
    {
        $this->bindEvents(); // try to bind events, if not yet done

        if ($chmod === UNDEFINED && $this->chmodDefined())
        {
            return $this->key('chmod');
        }
        else
        {
            echo $chmod;
            $chmod_success = chmod($this->getPath(), $chmod);
            if ($chmod_success)
            {
                $this->key('chmod', $chmod);
            }
            return $chmod_success;
        }

        return false;
    }


    /**
     * Get parent Directory of this instance
     *
     * @param int $chmod      If not specified, will use this instance's CHMOD as default
     * @return \IO\Directory
     */
    public function parent($chmod = null)
    {
        $parent_path = $this->getPathInfo()->getDirname();
        return new \IO\Directory($parent_path, false, is_null($chmod) ? $this->chmod : $chmod);
    }


    protected function bindEvents()
    {
        if ($this->eventsBinded)
        {
            return;
        }

        // Otherwise, proceed binding this/these event/s

        $this->eventsBinded = true;

        // Bind event for `chmod` key
        $this->keyEvent('chmod',
                new \Events\Event(
                        function($value, $key)
                        {
                            return is_int($value) ? decoct($value) : $value;
                        }));
    }


    /**
     * Prepare file for writing, including creating its parent directories and ancestors (freak, they're ancients I guess)
     *
     * @param string $path_to_file      Path to file
     * @param boolean $create           [optional] If the file should also be created
     * @param int $chmod                [optional] CHMOD permission to be applied
     * @return \IO\File
     */
    static public function Prepare($path_to_file, $create = false, $chmod = null)
    {
        $file = new File($path_to_file);
        $dirpath = $file->getPathInfo()->getDirname();
//        if ($file->getName() == "sample2.txt")
//            dd($dirpath);
        $directory = new \IO\Directory($dirpath);

        if (!$directory->exists())
        {
            $directory->createRecursively();
        }

        // reinstantiate, and return
        $file = new File(localpath($directory->getPath(), $file->getName()), $create, $chmod);
        return $file;
    }


}
