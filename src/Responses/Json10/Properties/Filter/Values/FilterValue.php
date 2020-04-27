<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Filter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\ImageFilter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\LabelFilter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\RangeSliderFilter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\SelectFilter;

abstract class FilterValue
{
    /** @var string */
    protected $name;

    /** @var bool */
    protected $selected;

    /** @var float */
    protected $weight;

    /** @var int|null */
    protected $frequency;

    public function __construct(array $filterValue)
    {
        // Continuously check if the values aren't set already, to allow filter-classes to manually set these
        // values.
        if ($this->name === null) {
            $this->name = ResponseHelper::getStringProperty($filterValue, 'value');
        }
        if ($this->selected === null) {
            $this->selected = ResponseHelper::getBoolProperty($filterValue, 'selected');
        }
        if ($this->weight === null) {
            $this->weight = ResponseHelper::getFloatProperty($filterValue, 'weight', true);
        }
        if ($this->frequency === null) {
            $this->frequency = ResponseHelper::getIntProperty($filterValue, 'frequency');
        }
    }

    public static function getInstance(Filter $filter, array $filterValue)
    {
        switch (true) {
            case $filter instanceof RangeSliderFilter:
                return new RangeSliderValue($filterValue);
            case $filter instanceof ImageFilter:
                return new ImageFilterValue($filterValue);
            case $filter instanceof SelectFilter:
            case $filter instanceof LabelFilter:
            default:
                return new DefaultFilterValue($filterValue);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isSelected()
    {
        return $this->selected;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return int|null
     */
    public function getFrequency()
    {
        return $this->frequency;
    }
}
