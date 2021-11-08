<?php

namespace FINDOLOGIC\Api\Requests;

use BadMethodCallException;
use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\RequestMethod;

/**
 * @internal
 */
class AlivetestRequest extends Request
{
    protected $endpoint = Endpoint::ALIVETEST;
    protected $method = RequestMethod::GET;

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
