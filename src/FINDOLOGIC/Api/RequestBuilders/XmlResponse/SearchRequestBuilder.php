<?php

namespace FINDOLOGIC\Api\RequestBuilders\XmlResponse;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\FindologicConfig;
use FINDOLOGIC\Api\Objects\XmlResponse;

class SearchRequestBuilder extends XmlResponseRequestBuilder
{
    protected $endpoint = Endpoint::SEARCH;

    public function __construct(FindologicConfig $config)
    {
        parent::__construct($config);
        $this->addRequiredParam(QueryParameter::QUERY);
    }

    /**
     * @inheritdoc
     * @return XmlResponse
     */
    public function sendRequest()
    {
        $this->checkRequiredParamsAreSet();
        $this->sendAlivetestRequest();

        $responseContent = $this->findologicClient->request($this->buildRequestUrl());
        return new XmlResponse($responseContent, $this->findologicClient->getResponseTime());
    }
}
