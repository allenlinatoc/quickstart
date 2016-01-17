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


//        $view = new Philo\Blade\Blade([
//
//                System::FrameworkViewsPath()
//
//            ], System::StoragePath());
//
//
//        echo $view->view()->make("exception", [ 'name' => 'Allen' ])->render();

//        var_dump($config->json("customExceptionHandler"));

        $this
                ->loadRoutes()
                ->initStorage();

        self::$instance = $this;
    }


    public function initStorage()
    {
        SystemTools::FixStoragePath();
    }


    protected function loadRoutes()
    {
        $routepath = MY_ETC_PATH . "/routes.bsv";

        if (!file_exists($routepath))
        {
            throw new RouteLoadException();
        }

        $loader = new FileLoader($routepath, true);
        $loader->read();
        $loader->getSha1();
        return $this;
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
        define('ROOT_PATH',         $this->rootpath);

        /**
         * @var string      Directory path to App's controllers
         */
        define('APP_CTRL_PATH',     ROOT_PATH . "/App/Controllers");

        /**
         * @var string      Directory path to App's models
         */
        define('APP_MODELS_PATH',   ROOT_PATH . "/App/Models");

        /**
         * @var string      Directory path to App's views
         */
        define('APP_VIEWS_PATH',    ROOT_PATH . "/App/Views");

        /**
         * @var string      Directory path of Quickstart framework
         */
        define('FW_PATH',           realpath(ROOT_PATH . "/Framework"));

        /**
         * @var string      Directory path to Quickstart configuration files
         */
        define('FW_ETC_PATH',       realpath(FW_PATH . "/Etc"));

        /**
         * @var string      Directory path to Storage files
         */
        define('FW_STORAGE_PATH',   realpath(FW_PATH . "/Storage"));

        /**
         * @var string      Directory path to Views
         */
        define('FW_VIEWS_PATH',     realpath(FW_PATH . "/Views"));

        /**
         * @var string      Directory path to Modules
         */
        define('MY_MODULES_PATH',   realpath(ROOT_PATH . "/Modules"));

        /**
         * @var string      Directory path to user configuration files
         */
        define('MY_ETC_PATH',       realpath(ROOT_PATH . "/Etc"));



        define('UNDEFINED',         "UNDEFINED(" . substr(sha1(rand()), rand(0, 12), 7) . ")");
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
