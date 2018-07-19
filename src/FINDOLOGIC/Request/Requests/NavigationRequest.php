<?php

namespace FINDOLOGIC\Request\Requests;

use FINDOLOGIC\Helpers\FindologicClient;
use FINDOLOGIC\Request\Request;

class NavigationRequest extends Request
{
    private $action = FindologicClient::NAVIGATION_ACTION;
    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
