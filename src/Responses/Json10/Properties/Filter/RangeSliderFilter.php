<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class RangeSliderFilter extends Filter
{
    /** @var float */
    protected $stepSize;

    /** @var string */
    protected $unit;

    /** @var Range */
    protected $totalRange;

    /** @var Range */
    protected $selectedRange;

    public function __construct(array $filter)
    {
        parent::__construct($filter);

        $this->stepSize = ResponseHelper::getFloatProperty($filter, 'stepSize');
        $this->unit = ResponseHelper::getStringProperty($filter, 'unit');

        $this->totalRange = new Range($filter['totalRange']);
        $this->selectedRange = new Range($filter['selectedRange']);
    }

    /**
     * @return float
     */
    public function getStepSize()
    {
        return $this->stepSize;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @return Range
     */
    public function getTotalRange()
    {
        return $this->totalRange;
    }

    /**
     * @return Range
     */
    public function getSelectedRange()
    {
        return $this->selectedRange;
    }
}
