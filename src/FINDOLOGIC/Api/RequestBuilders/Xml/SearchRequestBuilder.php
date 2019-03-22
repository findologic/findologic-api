<?php

namespace FINDOLOGIC\Api\RequestBuilders\Xml;

use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\ResponseObjects\Xml\XmlResponse;

class SearchRequestBuilder extends XmlRequestBuilder
{
    protected $endpoint = Endpoint::SEARCH;

    public function __construct(Config $config)
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

        $responseContent = $this->client->send($this->buildRequestUrl());
        return new XmlResponse($responseContent, $this->client->getResponseTime());
    }
}
