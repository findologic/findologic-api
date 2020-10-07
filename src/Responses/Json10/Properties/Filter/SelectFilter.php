<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

/**
 * Filter of type "select", known in the customer account as "Dropdown".
 */
class SelectFilter extends Filter
{
    /** @var int */
    protected $pinnedFilterValueCount;

    public function __construct(array $filter)
    {
        parent::__construct($filter);

        $this->pinnedFilterValueCount = ResponseHelper::getIntProperty($filter, 'pinnedFilterValueCount');
    }

    /**
     * @return int
     */
    public function getPinnedFilterValueCount()
    {
        return $this->pinnedFilterValueCount;
    }
}
