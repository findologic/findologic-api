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
        $this->min = ResponseHelper::getFloatProperty($filterValue['value'], 'min');
        $this->max = ResponseHelper::getFloatProperty($filterValue['value'], 'max');

        $this->name = sprintf('%.2f - %.2f', $this->min, $this->max);

        parent::__construct($filterValue);
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
