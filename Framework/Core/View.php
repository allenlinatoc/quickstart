<?php


use Philo\Blade\Blade;
use Exceptions\NullArgumentException;


/**
 * A View (based on Blade engine)
 */
class View extends Blade
{


    /**
     * Create new view
     *
     * @param string $viewPaths                     The path to view file. The conventions are (1) "/path/to/view" if located in App; and (2) "MODULE/path/to/view" if located in one of your module
     * @param string $cachePath                     [optional] Path to cache directory. Will use default storage path if NULL.
     * @param \Illuminate\Events\Dispatcher $events [optional] No idea what the hell this shit does.
     *
     * @throws NullArgumentException
     */
    public function __construct($viewPaths = array(), $cachePath = null, \Illuminate\Events\Dispatcher $events = null)
    {
        if (is_null($cachePath))
        {
            $cachePath = System::StoragePath();
        }

        parent::__construct($viewPaths, $cachePath, $events);
    }



    /**
     * Resolve a View path
     *
     * @param string $path      The path to be resolved
     *
     * @return string|false
     */
    static public function ResolvePath($path)
    {

        $result = false;

        $path = preg_replace('/(.+)(\.blade\.php)$/i', '$1', $path) . '.blade.php';

        if (!in_array(preg_match('/^\/.+/', $path), [0, false]))
        {
            // This is an app's view
            $result = System::ViewsPath($path);
        }
        else if (!in_array(preg_match('/^\$\/.+/i', $path), [0, false]))
        {
            // This is a System view
            //
            // "$/path/to/view" if System view (not recommended for non-system use)

            $result = System::FrameworkViewsPath(substr($path, 2));
        }
        else
        {
            // This is a module's view
            $result = System::ModulesPath(null, "/Views/" . $path);
        }

        return $result;
    }


    /**
     * Get path to directory based on provided path
     *
     * @param string $path      The path to be resolved
     */
    static public function GetDirectory($path)
    {

    }


    /**
     * Get the Blade name based on provided path
     *
     * @param string $path      The path to be resolved
     */
    static public function GetBlame($path)
    {

    }


    /**
     * Extract meta information from a path, such as [0] => Parent directory, [1] => Blade name
     *
     * @param string $path      The path to view file
     * @return array
     */
    static public function ExtractMeta($path)
    {
        $result = explode('|', preg_replace('/^(\$|\w+)?(\/.*)/', '$1|$2', trim($path)));
        $result[0] = trim($result[0]);

        // Check if a Module name or $ notation is available,
        // otherwise, set it to App's View path
        if (strlen($result[0]) > 0)
        {
            switch ($result[0])
            {
                case "$":
                {
                    // System
                    $result[0] = System::FrameworkViewsPath();
                    break;
                }
                default:
                {
                    // Module
                    $result[0] = System::ModulesPath($result[0], "/Views");
                    break;
                }
            }
        }
        else
        {
            // App
            $result[0] = System::ViewsPath();
        }

        $result[1] = rtrim(trim($result[1], '/'), '.blade.php');

        // It should be safe now
        return $result;
    }


}