<?php

namespace FINDOLOGIC\Api\RequestBuilders\XmlResponse;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Objects\XmlResponse;
use FINDOLOGIC\Api\RequestBuilders\RequestBuilder;

class NavigationRequestBuilder extends XmlResponseRequestBuilder
{
    protected $endpoint = Endpoint::NAVIGATION;

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
