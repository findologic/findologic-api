<?php

namespace FINDOLOGIC\Api\Requests;

use BadMethodCallException;
use FINDOLOGIC\Api\Definitions\Endpoint;

/**
 * @internal
 */
class AlivetestRequest extends Request
{
    protected $endpoint = Endpoint::ALIVETEST;
    protected $method = Request::METHOD_GET;

    /**
     * @internal
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function getBody()
    {
        throw new BadMethodCallException('Request body is not supported for alivetest requests');
    }
}
