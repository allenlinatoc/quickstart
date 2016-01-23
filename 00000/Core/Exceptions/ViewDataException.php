<?php

namespace Exceptions;

/**
 * Exception thrown when a View data does not exist
 *
 * @author Allen
 */
class ViewDataException extends \ExceptionManager
{

    protected $dataName;
    protected $view;


    /**
     * New ViewDataException instance
     *
     * @param \ViewManager $view           The source view
     * @param string $dataName      Name of data
     * @param \Exception $previous  Previous exception
     */
    public function __construct(\ViewManager $view, $dataName, \Exception $previous = null)
    {
        $this->view = $view;
        $this->dataName = $dataName;

        $message = sprintf("Undefined data \"%s\" under view \"%s\"",
                $this->getDataName(),
                $this->getView()->getPath()
        );

        parent::__construct($message, 0, $previous);
    }


    /**
     * Get the source view
     *
     * @return \ViewManager
     */
    public function getView()
    {
        return $this->view;
    }


    /**
     * Get name of data
     *
     * @return string
     */
    public function getDataName()
    {
        return $this->dataName;
    }


}
