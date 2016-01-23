<?php

/**
 * @var array   Array of helper names to load
 */
$files_to_load = [

    'data',

    'io',

    'string',

    'runtime'

];



foreach ($files_to_load as $file)
{
    require_once sprintf(__DIR__ . "/%s.helper.php", $file);
}

unset($files_to_load);