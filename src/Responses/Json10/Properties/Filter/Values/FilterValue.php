<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Values;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\ColorFilter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Filter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\ImageFilter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\LabelFilter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\RangeSliderFilter;
use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\SelectFilter;

abstract class FilterValue
{
    protected ?string $name = null;
    protected ?bool $selected = null;
    protected ?float $weight = null;
    protected ?int $frequency = null;

    /**
     * @param array<string, string|int|float|bool|null> $filterValue
     */
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

    /**
     * @param array<string, string|int|float|bool|null> $filterValue
     */
    public static function getInstance(Filter $filter, array $filterValue): FilterValue
    {
        switch (true) {
            case $filter instanceof RangeSliderFilter:
                return new RangeSliderValue($filterValue);
            case $filter instanceof ImageFilter:
                return new ImageFilterValue($filterValue);
            case $filter instanceof ColorFilter:
                return new ColorFilterValue($filterValue);
            case $filter instanceof SelectFilter:
            case $filter instanceof LabelFilter:
            default:
                return new DefaultFilterValue($filterValue);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isSelected(): bool
    {
        return $this->selected;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getFrequency(): ?int
    {
        return $this->frequency;
    }
}
