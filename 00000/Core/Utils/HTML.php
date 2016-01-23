<?php

namespace Utils;

/**
 * Utility-class for HTML
 *
 * @author Allen
 */
class HTML
{

    /**
     * Open new HTML node
     *
     * @param string $tag           Html tag
     * @param array $attributes     [optional] Attributes of this node
     * @param boolean $return       [optional] If HTML will be returned as string
     *
     * @return string
     */
    static public function Open($tag, array $attributes = array(), $return = false)
    {
        $result = sprintf("<%s %s>",
                strtolower(trim($tag)),
                call_user_func(function($attributes)
                        {
                            $result = "";
                            foreach ($attributes as $key => $value)
                            {
                                $result .= sprintf("%s = \"%s\"", trim($key), $value) . " ";
                            }
                            return rtrim($result);
                        }, $attributes)
            );

        if ($return)
        {
            return $result;
        }

        echo $result;
        return $result;
    }


    /**
     * Place an inline HTML node
     *
     * @param string $tag           Html tag
     * @param array $attributes     [optional] Attributes of this node
     * @param boolean $return       [optional] If HTML will be returned as string
     *
     * @return string
     */
    static public function Node($tag, array $attributes = array(), $return = false)
    {
        $result = sprintf("<%s %s />",
                strtolower(trim($tag)),
                call_user_func(function($attributes)
                        {
                            $result = "";
                            foreach ($attributes as $key => $value)
                            {
                                $result .= sprintf("%s = \"%s\"", trim($key), $value) . " ";
                            }
                            return rtrim($result);
                        }, $attributes)
            );

        if ($return)
        {
            return $result;
        }

        echo $return;
        return $return;
    }


    /**
     * Close an opened HTML node
     *
     * @param string $tag       The HTML tag to be closed
     * @param boolean $return   [optional] If HTML will be returned as string
     *
     * @return string
     */
    static public function Close($tag, $return = false)
    {
        $result = sprintf("</%s>", strtolower(trim($tag)));

        if ($return)
        {
            return $result;
        }

        echo $result;
        return $result;
    }


}
