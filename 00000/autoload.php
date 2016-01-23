<?php

require_once __DIR__ . "/Helpers/stub.php";

// System Autoloader

spl_autoload_register(function($classname)
{
    $rootpath = realpath(__DIR__ . "/Core") . "/";

    $absolutePath = $rootpath . str_replace("\\", "/", ltrim($classname, "\\")) . ".php";

    if (file_exists($absolutePath))
    {
        require_once $absolutePath;
        return;
    }

//    die("System class \"$classname\" does not exist as $absolutePath");


});


// Check for vendor autoload

$vendor_autoload = realpath(__DIR__ . "/../vendor/autoload.php");

if ($vendor_autoload !== false)
{
    require_once $vendor_autoload;
}