<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Builder;

use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Definitions\Endpoint;
use Psr\Http\Message\RequestInterface;

class ItemUpdateRequestBuilder extends RequestBuilder
{
    protected function getEndpoint(): string
    {
        return Endpoint::UPDATE;
    }

    public function buildRequest(Config $config): RequestInterface
    {
        // TODO: Implement getRequest() method.
    }

    public function reset(): void
    {
        // TODO: Implement reset() method.
    }
}
