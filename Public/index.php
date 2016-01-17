<?php

// Set internal encoding

mb_internal_encoding("UTF-8");


// Path separator definition

define("SEP", !in_array(preg_match("/^WIN/i", PHP_OS), [ false, 0 ]) ? "\\" : "/");

require_once __DIR__ . "/../Framework/autoload.php";


// Set exception handler

set_exception_handler(array("System", "HandleException"));


$quickstart = new Quickstart(__DIR__ . "/../");
$quickstart->start();