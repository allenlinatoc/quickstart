<?php

/**
 * The recommended parent Exception class to be inherited.
 *
 * @author Allen
 */
class ExceptionManager extends Exception
{

    protected $exceptionName;


    /**
     * New ExceptionManager instance
     *
     * @param string $message Exception message
     * @param int $code [optional] Exception code
     * @param \Exception $previous Previous exception
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        $this->exceptionName = __CLASS__;
        parent::__construct($message, $code, $previous);
    }


    /**
     * Get the name of this exception
     *
     * @return string
     */
    public function getExceptionName()
    {
        return $this->exceptionName;
    }


}