<?php

namespace Loaders;

use Exceptions\FileNotFoundException;
use Exceptions\FileWriteException;

/**
 * Class that loads a file
 *
 * @author Allen
 */
class FileLoader extends \KV
{

    private $sha1;
    private $path;
    private $contents;
    private $buffer_contents;


    /**
     * New FileLoader instance
     *
     * @param string $path              The path to target file
     * @param boolean $autoread         [optional] If file will be read during initiation, default is FALSE
     * @param boolean $autocreate       [optional] If file will be autocreate if it doesn't exist, default is FALSE
     * @throws FileNotFoundException
     * @throws FileWriteException
     */
    public function __construct($path, $autoread = false, $autocreate = false)
    {
        parent::__construct();
        $this->key([
            'autoread' => $autoread,
            'autocreate' => $autocreate
        ]);

        $this->path = $path;
        $realpath = realpath($path);
        if ($realpath === false)
        {
            // If autocreate, try to create file
            if ($this->key('autocreate') && !file_put_contents($this->path, ""))
            {
                // throw exception if autocreation failed
                throw new FileWriteException($path);
            }

            // Reload realpath
            $realpath = realpath($path);
        }

        $this->path = $realpath === false ? $path : $realpath;

        if ($this->key('autoread'))
        {
            $this->read();
        }
    }


    /**
     * Append a content
     *
     * @param string $contents
     */
    public function appendContents($contents)
    {
        if ($this->contents == null)
        {
            $this->contents = "";
        }

        $this->contents .= $contents;
    }


    /**
     * Check if this file exists
     *
     * @return boolean
     */
    public function exists()
    {
        return file_exists($this->path);
    }


    /**
     * Read file contents of this loader
     *
     * @param boolean $force    [optional] If specified, file contents will be forced to reload
     * @return string|null
     */
    public function read($force = false)
    {
        self::ExistOrThrow($this->getPath());

        if ($this->contents == null || $force)
        {
            $contents = file_get_contents($this->path);
            $this->contents = $contents === false ? null : $contents;
        }

        return $this->getContents();
    }


    /**
     * Load file as output buffer
     *
     * @param boolean $force    [optional] If specified, file's output buffer will be forced to reload
     * @return string
     */
    public function loadBuffer($force = false)
    {
        self::ExistOrThrow($this->getPath());

        if ($this->buffer_contents == null || $force)
        {
            ob_start();
            require $this->getPath();
            $this->buffer_contents = ob_get_clean();
        }

        return $this->getBufferContents();
    }


    private function getBufferContents()
    {
        return $this->buffer_contents;
    }


    public function getPath()
    {
        return $this->path;
    }


    public function getSha1()
    {
        self::ExistOrThrow($this->getPath());

        if ($this->sha1 == null)
            $this->sha1 = sha1_file($this->getPath());

        return $this->sha1;
    }


    /**
     * Get contents of this file loader
     *
     * @return string|null
     */
    protected function getContents()
    {
        $this->contents == null && $this->read();
        return $this->contents;
    }


    public function setContents($contents)
    {
        $this->contents = $contents;
    }


    protected function setPath($path)
    {
        $this->path = $path;
    }


    public function clear()
    {
        $this->contents = "";
    }


    /**
     * Try to reload the file
     *
     * @return string
     */
    public function tryReload()
    {
        self::ExistOrThrow($this->getPath());

        // If file changed, reload file contents
        if (sha1_file($this->getPath()) != $this->getSha1())
        {
            $this->read(true);
            $this->getSha1();
        }

        return $this->getContents();
    }


    /**
     * Try to reload file as output buffer
     *
     * @return string
     */
    public function tryReloadBuffer()
    {
        self::ExistOrThrow($this->getPath());

        if (sha1_file($this->getPath()) != $this->getSha1())
        {
            $this->loadBuffer(true);
            $this->getSha1();
        }

        return $this->getBufferContents();
    }


    public function write($contents = null, $append = false)
    {
        if ($contents != null)
            $this->contents = $contents;

        if ($append)
        {
            $this->appendContents($content);
        }

        $success = file_put_contents($this->getPath(), $this->getContents());

        if ($success === false)
            return $success;

        $this->sha1 = null;

        return true;
    }


    /**
     * Check if a file exists, otherwise, will throw an exception
     *
     * @param string $path  The path to file
     * @throws FileNotFoundException
     */
    public static function ExistOrThrow($path)
    {
        if (!file_exists($path))
        {
            throw new FileNotFoundException($path);
        }
    }


}
