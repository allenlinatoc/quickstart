<?php


use Philo\Blade\Blade;
use Exceptions\BladeCacheException;
use Exceptions\NullArgumentException;


/**
 * A View (based on Laravel's Blade engine)
 */
class ViewManager extends Blade
{

    /**
     *
     * @param string $viewpath
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function __construct($viewpath, \Illuminate\Events\Dispatcher $events = null)
    {
        if (is_empty($viewpath))
            throw new NullArgumentException($viewpath);

        $cachePath = self::InitCachePath();

        if (is_undefined($cachePath))
            throw new BladeCacheException();

        parent::__construct((array)$viewpath, $cachePath, $events);
    }


    /**
     * Render
     *
     * @param type $view
     * @param type $data
     * @param type $value
     */
    public function render($view, $data = null, $value = null)
    {
        $viewData = [ ];

        if (is_array($data))
        {
            $viewData = $data;
        }
        else if (is_string($data) && !is_empty($value))
        {
            $viewData = [ $data => $value ];
        }

        $this->view()->make($view, $viewData)->render();
    }


    /**
     * Initialize cache path
     *
     * @return string|UNDEFINED
     */
    static public function InitCachePath()
    {
        $path = __STORAGE . '/blades_cache';
        if (!is_dir($path))
        {
            if (mkdir($path, 0777, true) && is_dir($path))
            {
                return realpath($path);
            }
        }

        return UNDEFINED;
    }


}