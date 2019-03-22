<?php

namespace FINDOLOGIC\Api\RequestBuilders\Xml;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\ResponseObjects\Xml\XmlResponse;

class NavigationRequestBuilder extends XmlRequestBuilder
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

        $responseContent = $this->client->send($this->buildRequestUrl());
        return new XmlResponse($responseContent, $this->client->getResponseTime());
    }
}
