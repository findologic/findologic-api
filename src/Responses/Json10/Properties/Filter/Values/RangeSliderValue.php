<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class RangeSliderValue extends FilterValue
{
    protected ?bool $selected = false;
    protected ?float $min;
    protected ?float $max;

    public function __construct(array $filterValue)
    {
        $this->min = ResponseHelper::getFloatProperty($filterValue['value'], 'min');
        $this->max = ResponseHelper::getFloatProperty($filterValue['value'], 'max');

        $this->name = sprintf('%.2f - %.2f', $this->min, $this->max);

        parent::__construct($filterValue);
    }

    public function getMin(): ?float
    {
        return $this->min;
    }

    public function getMax(): ?float
    {
        return $this->max;
    }
}
