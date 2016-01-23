<?php

use Utils\HTML;


/**
 * Utility class and factory representing framework's system
 *
 * @author Allen
 */
class System
{


    /**
     * Get the root directory path to app's Controllers
     *
     * @param string    Path to be appended
     *
     * @return string|boolean
     */
    static public function ControllersPath($path = "")
    {
        return realpath(__CONTROLLERS . "/" . $path);
    }


    /**
     * Get the root directory path to app's Models
     *
     * @param string    Path to be appended
     *
     * @return string|boolean
     */
    static public function ModelsPath($path = "")
    {
        return realpath(__MODELS . "/" . $path);
    }
    

    /**
     * Get the root directory path to configuration files
     *
     * @param string    Path to be appended
     *
     * @return string|boolean
     */
    static public function ConfigsPath($path = "")
    {
        return realpath(__MY_CONFIGS . "/" . $path);
    }


    /**
     * Get the root directory path to framework configuration files
     *
     * @param string    Path to be appended
     *
     * @return string|boolean
     */
    static public function FrameworkConfigsPath($path = "")
    {
        return realpath(__FW_CONFIGS . "/" . $path);
    }


    /**
     * Get the framework directory path
     *
     * @param string    Path to be appended
     *
     * @return string|boolean
     */
    static public function FrameworkDirPath($path = "")
    {
        return realpath(__FW . "/" . $path);
    }


    /**
     * Get directory path to framework views
     *
     * @param string    Path to be appended
     *
     * @return string|boolean
     */
    static public function FrameworkViewsPath($path = "")
    {
        return realpath(FW_VIEWS_PATH . "/" . $path);
    }


    /**
     * Get the root directory path of everything
     *
     * @param string    Path to be appended
     *
     * @return string|boolean
     */
    static public function RootPath($path = "")
    {
        return realpath(__ROOTPATH . "/" . $path);
    }


    /**
     * Get the storage directory path
     *
     * @param string    Path to be appended
     *
     * @return string|boolean
     */
    static public function StoragePath($path = "")
    {
        return realpath(__STORAGE . "/" . $path);
    }


    /**
     * Get the modules directory path
     *
     * @param string $module    [optional] Target module's name
     * @param string $path
     *
     * @return string|boolean
     */
    static public function ModulesPath($module = null, $path = "")
    {
        return realpath(
                str("{0}/{1}{2}",
                        __MY_MODULES,
                        ($module == null ? "" : $module),
                        "/" . $path));
    }


    /**
     * Method to implement custom exception handling
     *
     * @param string    Path to be appended
     *
     * @param mixed $exception
     */
    static public function HandleException($exception)
    {
        $engineConf = Configs\EngineConfiguration::Instance();

        if (!$engineConf->get("customExceptionHandler"))
        {
            throw $exception;
        }

        $meta = [
            "code" => $exception->getCode(),
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
            "previous" => $exception->getPrevious(),
            "trace" => $exception->getTrace()
        ];

        if ($exception instanceof ExceptionManager)
            $meta["name"] = $exception->getExceptionName();

        echo view("$/exception.phtml", $meta);
    }



}
