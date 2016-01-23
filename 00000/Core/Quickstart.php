<?php


use Exceptions\RouteLoadException;
use Loaders\FileLoader;

/**
 * I am the Alpha and the Omega
 *
 * @author Allen
 */
class Quickstart
{

    static private $instance = null;
    private $rootpath;


    /**
     * New Quickstart instance
     *
     * @param string $rootpath Path to system root
     */
    public function __construct($rootpath)
    {
        $this->rootpath = realpath($rootpath);
        if ($this->rootpath === false)
        {
            trigger_error("Unable to start your app when root path is invalid");
        }


        # Initialize constants

        $this->defineConstants();

        $this->_loadRoutes();

        self::$instance = $this;
    }


    protected function _loadRoutes()
    {
        $routepath = __MY_CONFIGS . "/routes.yml";

        if (!file_exists($routepath))
        {
            throw new RouteLoadException();
        }

        $loader = new FileLoader($routepath, true);
        $loader->read();
        $loader->getSha1();
    }


    public function start()
    {

    }

    public function getRootpath()
    {
        return $this->rootpath;
    }


    static public function GetURI()
    {

    }


    public function defineConstants()
    {
        //----------------------------------------------------------------------
        // Must be called first, before "System" class
        //----------------------------------------------------------------------

        /**
         * @var string      Root directory path of everything
         */
        define('__ROOTPATH',         $this->rootpath);

        /**
         * @var string      Directory path to App's controllers
         */
        define('__CONTROLLERS',         realpath(__ROOTPATH . "/app/controllers"));

        /**
         * @var string      Directory path to App's models
         */
        define('__MODELS',              realpath(__ROOTPATH . "/app/models"));

        /**
         * @var string      Directory path to views
         */
        define('__VIEWS',               realpath(__ROOTPATH . '/views'));

        /**
         * @var string      Directory path to Storage files
         */
        define('__STORAGE',          realpath(__ROOTPATH . "/storage"));

        /**
         * @var string      Directory path of Quickstart framework
         */
        define('__FW',                  realpath(__ROOTPATH . "/00000"));

        /**
         * @var string      Directory path to Quickstart configuration files
         */
        define('__FW_CONFIGS',          realpath(__FW . "/Etc"));

        /**
         * @var string      Directory path to Quickstart system views
         */
        define('__FW_VIEWS',            realpath(__FW . '/Views'));

        /**
         * @var string      Directory path to Modules
         */
        define('__MY_MODULES',          realpath(__ROOTPATH . "/modules"));

        /**
         * @var string      Directory path to user configuration files
         */
        define('__MY_CONFIGS',          realpath(__ROOTPATH . "/etc"));

        /**
         * @var string      Virtual constant of UNDEFINED value
         */
        define('UNDEFINED',             "UNDEFINED(" . substr(sha1(rand()), rand(0, 12), 7) . ")");
    }


    /**
     * Get current framework instance
     *
     * @return Quickstart
     */
    static public function Instance()
    {
        return self::$instance;
    }

}
