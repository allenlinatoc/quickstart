<?php

namespace Common;

/**
 * Interface for JSON objects
 *
 * @author Allen
 */
interface IJsonable
{

    function json($key, $value = null);
    function toJSON();

}
