<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\SearchNavigation;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;

class NavigationRequest extends SearchNavigationRequest
{
    protected string $endpoint = Endpoint::NAVIGATION;

    /**
     * Sets the selected param. It is used to tell FINDOLOGIC on which navigation page the user is.
     */
    public function setSelected(string $filterName, string $value): self
    {
        $this->addParam(QueryParameter::SELECTED, [$filterName => [$value]]);

        return $this;
    }
}
