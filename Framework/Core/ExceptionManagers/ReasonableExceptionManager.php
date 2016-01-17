<?php

namespace ExceptionManagers;

/**
 * Exception manager for exceptions with varying reasons
 *
 * @author Allen
 */
class ReasonableExceptionManager extends \ExceptionManager
{

    protected $reason;


    public function __construct($reason, $message = "", $code = 0, \Exception $previous = null)
    {
        $this->reason = $reason;
        parent::__construct($message, $code, $previous);
    }


    /**
     * Get current reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }


}
