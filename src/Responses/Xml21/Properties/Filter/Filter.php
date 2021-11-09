<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties\Filter;

use FINDOLOGIC\Api\Definitions\FilterType;
use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Attributes;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item\Item;
use SimpleXMLElement;

abstract class Filter
{
    /** @var int|null $itemCount */
    protected $itemCount;

    /** @var string|null $cssClass */
    protected $cssClass;

    /** @var string|null $noAvailableFiltersText */
    protected $noAvailableFiltersText;

    /** @var string $name */
    protected $name;

    /** @var string $display */
    protected $display;

    /** @var string $select */
    protected $select;

    /** @var int $selectedItemCount */
    protected $selectedItemCount = 0;

    /** @var Item[] */
    protected $selectedItems = [];

    /** @var Attributes|null $attributes */
    protected $attributes;

    /** @var Item[] $items */
    protected $items = [];

    public function __construct(SimpleXMLElement $response)
    {
        $this->itemCount = ResponseHelper::getIntProperty($response, 'itemCount', true);
        $this->cssClass = ResponseHelper::getStringProperty($response, 'cssClass');
        $this->noAvailableFiltersText = ResponseHelper::getStringProperty($response, 'noAvailableFiltersText');
        $this->name = ResponseHelper::getStringProperty($response, 'name');
        $this->display = ResponseHelper::getStringProperty($response, 'display');
        $this->select = ResponseHelper::getStringProperty($response, 'select');
        $this->selectedItemCount = ResponseHelper::getIntProperty($response, 'selectedItems') ?: 0;

        if ($response->attributes) {
            $this->attributes = new Attributes($response->attributes);
        }

        if ($response->items) {
            $this->fetchItems($response->items);
        }
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

    private function fetchItems(SimpleXMLElement $items)
    {
        foreach ($items->children() as $item) {
            $name = ResponseHelper::getStringProperty($item, 'name');
            $filterItem = Item::getInstance($this, $item);

            if ($filterItem->isSelected()) {
                $this->selectedItems[$name] = $filterItem;
            }

            $this->items[$name] = $filterItem;
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
    public function getSelectedItemCount()
    {
        return $this->selectedItemCount;
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

    /**
     * @return Item[]
     */
    public function getSelectedItems()
    {
        return $this->selectedItems;
    }
}
