<?php

use Loaders\FileLoader;


/**
 * A data storage file
 *
 * @author Allen
 */
class Storage extends FileLoader
{

    private $name;


    /**
     * Create Storage instance
     *
     * @param string $name Storage name
     */
    public function __construct($name)
    {
        $this->name = $name;
        parent::__construct(self::StoragePath($this->getName()), false, true);
    }


    /**
     * Get storage name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Generate storage path from a name
     *
     * @param string $name
     * @return string
     */
    static public function StoragePath($name)
    {
        return System::StoragePath(str("/{0}.data"), $name);
    }



}
