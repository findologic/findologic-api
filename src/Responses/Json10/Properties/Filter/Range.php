<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Range
{
    protected float $min;
    protected float $max;

    public function __construct(array $range)
    {
        $this->min = ResponseHelper::getFloatProperty($range, 'min');
        $this->max = ResponseHelper::getFloatProperty($range, 'max');
    }

    public function getMin(): float
    {
        return $this->min;
    }

    public function getMax(): float
    {
        return $this->max;
    }
}
