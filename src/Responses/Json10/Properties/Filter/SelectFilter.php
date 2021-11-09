<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

/**
 * Filter of type "select", known in the customer account as "Dropdown".
 */
class SelectFilter extends Filter
{
    protected int $pinnedFilterValueCount;

    public function __construct(array $filter)
    {
        parent::__construct($filter);

        $this->pinnedFilterValueCount = ResponseHelper::getIntProperty($filter, 'pinnedFilterValueCount');
    }

    public function getPinnedFilterValueCount(): int
    {
        return $this->pinnedFilterValueCount;
    }
}
