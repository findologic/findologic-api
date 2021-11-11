<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item;

use FINDOLOGIC\Api\Definitions\Defaults;
use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\CategoryFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\ColorPickerFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Filter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\LabelTextFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\RangeSliderFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\SelectDropdownFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\VendorImageFilter;
use SimpleXMLElement;

abstract class Item
{
    protected string $name;
    protected ?float $weight;
    protected ?int $frequency;
    protected bool $selected;

    public function __construct(SimpleXMLElement $item)
    {
        $this->name = ResponseHelper::getStringProperty($item, 'name') ?? Defaults::EMPTY;
        $this->weight = ResponseHelper::getFloatProperty($item, 'weight');
        $this->frequency = ResponseHelper::getIntProperty($item, 'frequency', true);
        $this->selected = ResponseHelper::getBoolProperty($item->attributes(), 'selected') ? true : false;
    }

    public static function getInstance(Filter $filter, SimpleXMLElement $item): Item
    {
        switch (true) {
            case $filter instanceof CategoryFilter:
                return new CategoryItem($item, $filter);
            case $filter instanceof ColorPickerFilter:
                return new ColorItem($item);
            case $filter instanceof RangeSliderFilter:
                return new RangeSliderItem($item);
            case $filter instanceof VendorImageFilter:
                return new VendorImageItem($item);
            case $filter instanceof SelectDropdownFilter:
            case $filter instanceof LabelTextFilter:
            default:
                return new DefaultItem($item);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function getFrequency(): ?int
    {
        return $this->frequency;
    }

    public function isSelected(): bool
    {
        return $this->selected;
    }
}
