<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Builder;

use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Definitions\Endpoint;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class AliveTestRequestBuilder extends RequestBuilder
{
    protected function getEndpoint(): string
    {
        return Endpoint::ALIVETEST;
    }

    public function buildRequest(Config $config): RequestInterface
    {
        return new Request('GET', $config->getFullApiUrl() . $this->getEndpoint());
    }

    public function reset(): void
    {
        // Nothing to do here.
    }
}
