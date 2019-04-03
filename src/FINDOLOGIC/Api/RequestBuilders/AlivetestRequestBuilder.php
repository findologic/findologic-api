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
     */
    public function sendRequest()
    {
    }
}
