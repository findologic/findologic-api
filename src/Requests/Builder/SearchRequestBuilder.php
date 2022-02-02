<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Builder;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;

class SearchRequestBuilder extends ResultRequestBuilderBase
{
    protected function getEndpoint(): string
    {
        return Endpoint::SEARCH;
    }

    public function setQuery(string $query): self
    {
        $this->setParam(QueryParameter::QUERY, $query);

        return $this;
    }

    public function setShoppingGuide(string $shoppingGuideName): self
    {
        $this->setParam(QueryParameter::SHOPPING_GUIDE, $shoppingGuideName);

        return $this;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->setParam(QueryParameter::IDENTIFIER, $identifier);

        return $this;
    }
}
