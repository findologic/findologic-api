<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties\Filter;

use FINDOLOGIC\Api\Definitions\FilterType;
use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Attributes;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Item;
use SimpleXMLElement;

abstract class Filter
{
    /** @var int|null $itemCount */
    private $itemCount;

    /** @var string|null $cssClass */
    private $cssClass;

    /** @var string|null $noAvailableFiltersText */
    private $noAvailableFiltersText;

    /** @var string $name */
    private $name;

    /** @var string $display */
    private $display;

    /** @var string $select */
    private $select;

    /** @var int $selectedItems */
    private $selectedItems = 0;

    /** @var Attributes|null $attributes */
    private $attributes;

    /** @var Item[] $items */
    private $items = [];

    public function __construct(SimpleXMLElement $response)
    {
        $this->itemCount = ResponseHelper::getIntProperty($response, 'itemCount', true);
        $this->cssClass = ResponseHelper::getStringProperty($response, 'cssClass');
        $this->noAvailableFiltersText = ResponseHelper::getStringProperty($response, 'noAvailableFiltersText');
        $this->name = ResponseHelper::getStringProperty($response, 'name');
        $this->display = ResponseHelper::getStringProperty($response, 'display');
        $this->select = ResponseHelper::getStringProperty($response, 'select');
        $this->selectedItems = ResponseHelper::getIntProperty($response, 'selectedItems') ?: 0;

        if ($response->attributes) {
            $this->attributes = new Attributes($response->attributes);
        }

        if ($response->items) {
            // Get the first <items> element, containing all <item> elements.
            foreach ($response->items[0] as $item) {
                $itemName = ResponseHelper::getStringProperty($item, 'name');
                $this->items[$itemName] = new Item($item);
            }
        }
    }

    /**
     * @return int|null
     */
    public function getItemCount()
    {
        return $this->itemCount;
    }

    /**
     * @return string|null
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * @return string|null
     */
    public function getNoAvailableFiltersText()
    {
        return $this->noAvailableFiltersText;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * @return string
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @return int
     */
    public function getSelectedItems()
    {
        return $this->selectedItems;
    }

    /**
     * @return Attributes|null
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    public static function getInstance(SimpleXMLElement $filter)
    {
        $filterName = ResponseHelper::getStringProperty($filter, 'name');

        if ($filterName === 'cat') {
            return new CategoryFilter($filter);
        }

        $filterType = ResponseHelper::getStringProperty($filter, 'type');

        switch ($filterType) {
            case FilterType::SELECT:
                return new SelectDropdownFilter($filter);
            case FilterType::RANGE_SLIDER:
                return new RangeSliderFilter($filter);
            case FilterType::VENDOR_IMAGE:
            case FilterType::VENDOR_IMAGE_ALTERNATIVE:
                return new VendorImageFilter($filter);
            case FilterType::COLOR:
            case FilterType::COLOR_ALTERNATIVE:
                return new ColorPickerFilter($filter);
            case FilterType::LABEL:
            default:
                return new LabelTextFilter($filter);
        }
    }
}
