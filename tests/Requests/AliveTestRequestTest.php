<?php

namespace FINDOLOGIC\Api\Tests\Requests;

use BadMethodCallException;
use FINDOLOGIC\Api\Requests\AlivetestRequest;
use FINDOLOGIC\Api\Tests\TestBase;

class AliveTestRequestTest extends TestBase
{
    public function testGetBodyIsNotSupported()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Request body is not supported for alivetest requests');

        $suggestRequest = new AlivetestRequest();
        $suggestRequest->getBody();
    }
}
