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
    protected string $endpoint = Endpoint::ALIVETEST;
    protected string $method = RequestMethod::GET;

    /**
     * @internal
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function getBody(): ?string
    {
        throw new BadMethodCallException('Request body is not supported for alivetest requests');
    }
}
