<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\CategoryFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\ColorPickerFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\DropdownFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Filter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\LabelFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\LabelTextFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\RangeSliderFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\SelectDropdownFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\SelectFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\TextFilter;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\ImageFilter;
use SimpleXMLElement;

abstract class Item
{
    /** @var string $name */
    protected $name;

    /** @var float|null $weight */
    protected $weight;

    /** @var int|null $frequency */
    protected $frequency;

    /** @var bool $selected */
    protected $selected;

    public function __construct(SimpleXMLElement $item)
    {
        $this->name = ResponseHelper::getStringProperty($item, 'name');
        $this->weight = ResponseHelper::getFloatProperty($item, 'weight');
        $this->frequency = ResponseHelper::getIntProperty($item, 'frequency', true);
        $this->selected = ResponseHelper::getBoolProperty($item->attributes(), 'selected') ? true : false;
    }

    public static function getInstance(Filter $filter, SimpleXMLElement $item)
    {
        switch (true) {
            case $filter instanceof CategoryFilter:
                return new CategoryItem($item, $filter);
            case $filter instanceof ColorPickerFilter:
                return new ColorItem($item);
            case $filter instanceof RangeSliderFilter:
                return new RangeSliderItem($item);
            case $filter instanceof ImageFilter:
                return new ImageItem($item);
            case $filter instanceof TextFilter:
            case $filter instanceof SelectFilter:
            case $filter instanceof SelectDropdownFilter:
            case $filter instanceof LabelTextFilter:
            case $filter instanceof LabelFilter:
            case $filter instanceof DropdownFilter:
            default:
                return new DefaultItem($item);
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
     * @return float|null
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

    /**
     * @return bool
     */
    public function isSelected()
    {
        return $this->selected;
    }
}
