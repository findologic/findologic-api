<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class RangeSliderFilter extends Filter
{
    protected float $stepSize;
    protected string $unit;
    protected Range $totalRange;
    protected Range $selectedRange;

    public function __construct(array $filter)
    {
        parent::__construct($filter);

        $this->stepSize = ResponseHelper::getFloatProperty($filter, 'stepSize');
        $this->unit = ResponseHelper::getStringProperty($filter, 'unit');

        $this->totalRange = new Range($filter['totalRange']);
        $this->selectedRange = new Range($filter['selectedRange']);
    }

    public function getStepSize(): float
    {
        return $this->stepSize;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function getTotalRange(): Range
    {
        return $this->totalRange;
    }

    public function getSelectedRange(): Range
    {
        return $this->selectedRange;
    }
}
