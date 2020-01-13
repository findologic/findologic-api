<?php

namespace FINDOLOGIC\Api\Requests;

use FINDOLOGIC\Api\Definitions\Endpoint;

/**
 * @internal
 */
class AlivetestRequest extends Request
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
