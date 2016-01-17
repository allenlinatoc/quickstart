<?php

namespace Configs;

use Loaders\JSONLoader;

/**
 * Class representing an instance of app's configuration
 *
 * @author Allen
 */
class AppConfiguration extends JSONLoader
{

    static private $instance;


    public function __construct()
    {
        parent::__construct(\System::FrameworkConfigsPath("/app.json"), true);
    }


    /**
     * Get current AppConfiguration instance
     *
     * @param boolean $force    [optional] If force instance renewal
     * @return \Configs\AppConfiguration
     */
    static public function Instance($force = false)
    {
        if (self::$instance == null || $force)
        {
            self::$instance = new AppConfiguration();
        }

        return self::$instance;
    }


}
