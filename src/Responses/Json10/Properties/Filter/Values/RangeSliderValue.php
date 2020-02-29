<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class RangeSliderValue extends FilterValue
{
    protected $selected = false;

    /** @var float */
    protected $min;

    /** @var float */
    protected $max;

    public function __construct(array $filterValue)
    {
        parent::__construct($filterValue);

        $this->min = ResponseHelper::getFloatProperty($filterValue['value'], 'min');
        $this->max = ResponseHelper::getFloatProperty($filterValue['value'], 'max');
    }
}
