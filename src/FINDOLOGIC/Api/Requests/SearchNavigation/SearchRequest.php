<?php

namespace FINDOLOGIC\Api\Requests\SearchNavigation;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;

class SearchRequest extends SearchNavigationRequest
{
    protected $endpoint = Endpoint::SEARCH;

    public function __construct()
    {
        parent::__construct();
        $this->addRequiredParam(QueryParameter::QUERY);
    }
}
