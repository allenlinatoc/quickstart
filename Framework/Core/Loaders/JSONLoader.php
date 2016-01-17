<?php

namespace Loaders;

use Common\IJsonable;

/**
 * File loader for JSON files
 *
 * @author Allen
 */
class JSONLoader extends FileLoader implements IJsonable
{

    private $json_contents;


    /**
     * New JSONLoader instance
     *
     * @param string $path          Absolute path to JSON file
     * @param boolean $autoread     [optional] If file shall be autoread
     * @param boolean $autocreate   [optional] If file shall be autocreated if it doesn't exist
     *
     * @throws \Exceptions\JSONLoaderException
     */
    public function __construct($path, $autoread = false, $autocreate = false)
    {
        parent::__construct($path, $autoread, $autocreate);
        if ($this->getContents() === false)
        {
            throw new \Exceptions\JSONLoaderException($this->getPath());
        }
    }


    /**
     * Get contents of this loader
     *
     * @return string
     */
    public function getContents()
    {
        $contents = parent::getContents();

        if ($this->json_contents != null)
        {
            $json_contents = $this->json_contents;
        }
        else
        {
            $json_contents = json_decode($contents, true);
        }

        $this->json_contents = empty($json_contents) ? null : $json_contents;
        return $this->json_contents;
    }


    public function read($force = false)
    {
        $json_contents = parent::read($force);

        $this->json_contents = empty($json_contents) ? null : $json_contents;

        return $this->json_contents;
    }


    public function tryReload()
    {
        return $this->read(true);
    }


    public function write($contents = null, $append = false)
    {
        parent::write($contents);
    }


    /**
     * Get or set a JSON key value
     *
     * @param string $key_or_dimension       The key or JSON dimension, which value will be assigned
     * @param string $value     [optional] If specified, the value to be assigned to this key
     * @return string
     */
    public function json($key_or_dimension, $value = UNDEFINED)
    {
        if ($value == UNDEFINED)
        {
            // If target key is single-dimensional
            if (in_array(preg_match('/^(\{.+\})+$/', $key_or_dimension), [ false, 0 ]))
            {
                if (isset($this->json_contents[$key_or_dimension]))
                {
                    return $this->json_contents[$key_or_dimension];
                }
            }
            // If target key is multi-dimensional
            else
            {
                return \Utils\Arrays::Traverse($this->json_contents, $key_or_dimension);
            }

            return UNDEFINED;
        }
    }


    /**
     * Convert this instance to JSON string
     *
     * @return string|null
     */
    public function toJSON()
    {
        return json_encode($this->json_contents);
    }

}
