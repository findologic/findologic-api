<?php

namespace FINDOLOGIC\Request\Requests;

use FINDOLOGIC\Helpers\FindologicClient;
use FINDOLOGIC\Request\Request;

class SuggestRequest extends Request
{
    private $action = FindologicClient::SUGGEST_ACTION;
    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
