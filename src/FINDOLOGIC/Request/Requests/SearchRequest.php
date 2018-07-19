<?php

namespace FINDOLOGIC\Request\Requests;

use FINDOLOGIC\Helpers\FindologicClient;
use FINDOLOGIC\Request\Request;

class SearchRequest extends Request
{
    private $action = FindologicClient::SEARCH_ACTION;
    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
