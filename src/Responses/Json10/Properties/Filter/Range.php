<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Range
{
    /** @var float */
    protected $min;

    /** @var float */
    protected $max;

    public function __construct(array $range)
    {
        $this->min = ResponseHelper::getFloatProperty($range, 'min');
        $this->max = ResponseHelper::getFloatProperty($range, 'max');
    }

    /**
     * @return float
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @return float
     */
    public function getMax()
    {
        return $this->max;
    }
}
