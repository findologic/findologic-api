<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Builder;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;

class NavigationRequestBuilder extends ResultRequestBuilderBase
{
    protected function getEndpoint(): string
    {
        return Endpoint::NAVIGATION;
    }

    public function setSelected(string $name, $value): self
    {
        $this->setParam(QueryParameter::SELECTED, [$name => [$value]]);

        return $this;
    }
}
