<?php

namespace ExceptionManagers;

/**
 * For exceptions requiring data dump
 *
 * @author Allen
 */
class ValceptionManager extends \ExceptionManager
{

    protected $data_dump;
    protected $data;


    public function __construct($data, $message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }


    /**
     * Get data dump
     *
     * @return string
     */
    public function getDataDump()
    {
        if ($this->data_dump == null)
            $this->data_dump = dump($this->data, true);
        
        return $this->data_dump;
    }


    /**
     * Get current data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }


}
