<?php

namespace FINDOLOGIC\Api\RequestBuilders\Xml20;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;

class SearchRequestBuilder extends XmlRequestBuilder
{
    protected $endpoint = Endpoint::SEARCH;

    public function __construct()
    {
        parent::__construct();
        $this->addRequiredParam(QueryParameter::QUERY);
    }
}
