<?php

use Exceptions\NoSuchHeaderException;

/**
 * Class representing a response
 *
 * @author Allen
 */
class Response extends \QuickClass
{

    private $content;
    private $code = 200;
    private $headers = array();
    private $additionals = array();


    /**
     * New Response instance
     *
     * @param string $content   The contents of response
     * @param int $code         [optional] HTTP response code
     * @param array $headers    [optional] Array of HTTP headers
     */
    public function __construct($content, $code = 200, $headers = array())
    {
        if (intval($code) != 0)
        {
            $this->code = $code;
        }

        if (is_array($headers))
        {
            $this->headers = $headers;
        }

        if (isset($this->headers['code']))
        {
            $this->code = $this->headers['code'];
            unset($this->headers['code']);
        }

        $this->setContent($content);
    }


    /**
     * Get or set an additional string param for a certain header, in such format: "Header: value; (additional params here)"
     *
     * @param string $headerName    The header name of the additional data
     * @param string $additional    [optional] If specified, the value will be set as additional string param for specified header
     * @return boolean|string
     */
    public function additional($headerName, $additional = null)
    {
        if ($additional == null)
        {
            if (!isset($this->additionals[$headerName]))
            {
                return false;
            }

            return $this->additionals[$headerName];
        }

        $this->additionals[$headerName] = $additional;
    }


    /**
     * Get or set a header
     *
     * @param string $headerName    Name of header
     * @param string $value         [optional] If specified, the value will be set as name of this header
     * @param string $additional    [optional] If specified, the additional string param to be set for specified header
     * @return boolean|string
     */
    public function header($headerName, $value = null, $additional = null)
    {
        // If trying to get the value
        if ($value == null)
        {
            if (!isset($this->headers[$headerName]))
            {
                return false;
            }

            return $this->headers[$headerName];
        }

        $this->headers[$headerName] = $value;

        // If trying to set an additional string param for this header
        if ($additional != null)
        {
            $this->additionals[$headerName] = $additional;
        }
    }


    /**
     * Render this response
     *
     * @param boolean $return   [optional] If rendered response shall be returned as buffer string
     * @return string
     */
    public function render($return = false)
    {
        header_remove();

        $result = (string)$this;

        if ($return)
        {
            return $result;
        }

        die($result);
    }


    /**
     * Get HTTP response code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }


    /**
     * Get content of this response
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


    /**
     * Set HTTP response code
     *
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }


    /**
     * Set response content
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }


    public function __toString()
    {
        ob_start();
        http_response_code($this->getCode());
        foreach ($this->headers as $key => $value)
        {
            $additional = isset($this->additionals[$key]) ? $this->additionals[$key] : false;
            header(str("{0}: {1}{2}",
                    $key,
                    $value,
                    $additional ? str("; {0}", $additional) : ""));
        }
        echo $this->getContent();
        $ob = ob_get_clean();
        return $ob;
    }

}
