<?php

namespace Configs;

use Loaders\JSONLoader;

/**
 * Class representing an instance of configuration
 *
 * @author Allen
 */
class EngineConfiguration extends JSONLoader
{

    static private $instance = null;


    public function __construct()
    {
        parent::__construct(\System::FrameworkConfigsPath("/engine.json"), true);
    }


    /**
     * Get current EngineConfiguration instance
     *
     * @param boolean $force    [optional] If force instance renewal
     * @return \Configs\EngineConfiguration
     */
    static public function Instance($force = false)
    {
        if (self::$instance == null || $force)
        {
            self::$instance = new EngineConfiguration();
        }

        return self::$instance;
    }

}