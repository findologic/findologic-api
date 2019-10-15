<?php

namespace FINDOLOGIC\Api\Responses\Xml21;

use FINDOLOGIC\Api\Responses\Response;

class Xml21Response extends Response
{
    public function __construct($response, $responseTime = null)
    {
        parent::__construct($response, $responseTime);
    }

    protected function buildResponseElementInstances($response)
    {
        // TODO: Implement buildResponseElementInstances() method.
    }
}
