<?php

namespace FINDOLOGIC\Api\RequestBuilders\JsonResponse;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\FindologicConfig;
use FINDOLOGIC\Api\Objects\JsonResponse;
use FINDOLOGIC\Api\RequestBuilders\RequestBuilder;

class SuggestionRequestBuilder extends RequestBuilder
{
    protected $endpoint = Endpoint::SUGGESTION;

    public function __construct(FindologicConfig $config)
    {
        parent::__construct($config);
        $this->addRequiredParam(QueryParameter::QUERY);
    }

    /**
     * @inheritdoc
     * @return JsonResponse
     */
    public function sendRequest()
    {
        $this->checkRequiredParamsAreSet();

        $responseContent = $this->findologicClient->request($this->buildRequestUrl());
        return new JsonResponse($responseContent, $this->findologicClient->getResponseTime());
    }
}
