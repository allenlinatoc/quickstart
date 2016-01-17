<?php

namespace Responses;

/**
 * Class representing a response from a view
 *
 * @author Allen
 */
class ViewResponse extends \Response
{

    private $view;


    public function __construct(\View $view, $code = 200, $headers = array())
    {
        $headers["Content-type"] = "text/html";
        parent::__construct($view->render(true), $code, $headers);
    }

}
