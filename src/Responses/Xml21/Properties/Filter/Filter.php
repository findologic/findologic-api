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
    protected ?int $itemCount;
    protected ?string $cssClass;
    protected ?string $noAvailableFiltersText;
    protected string $name;
    protected string $display;
    protected string $select;
    protected int $selectedItemCount = 0;
    /** @var Item[] */
    protected array $selectedItems = [];
    protected ?Attributes $attributes;
    /** @var Item[] $items */
    protected array $items = [];

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

    public static function getInstance(SimpleXMLElement $filter): Filter
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

    private function fetchItems(SimpleXMLElement $items): void
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

    public function getItemCount(): ?int
    {
        return $this->itemCount;
    }

    public function getCssClass(): ?string
    {
        return $this->cssClass;
    }

    public function getNoAvailableFiltersText(): ?string
    {
        return $this->noAvailableFiltersText;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDisplay(): string
    {
        return $this->display;
    }

    public function getSelect(): string
    {
        return $this->select;
    }

    public function getSelectedItemCount(): int
    {
        return $this->selectedItemCount;
    }

    public function getAttributes(): ?Attributes
    {
        return $this->attributes;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return Item[]
     */
    public function getSelectedItems(): array
    {
        return $this->selectedItems;
    }
}
