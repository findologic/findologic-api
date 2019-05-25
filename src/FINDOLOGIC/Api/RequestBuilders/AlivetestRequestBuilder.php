<?php

namespace FINDOLOGIC\Api\RequestBuilders;

use FINDOLOGIC\Api\Definitions\Endpoint;

/**
 * @internal
 */
class AlivetestRequestBuilder extends RequestBuilder
{
    protected $endpoint = Endpoint::ALIVETEST;

    /**
     * @internal
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }
}
