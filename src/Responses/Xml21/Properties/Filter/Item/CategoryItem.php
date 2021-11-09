<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\CategoryFilter;
use SimpleXMLElement;

class CategoryItem extends Item
{
    /** @var CategoryItem[] $items */
    protected array $items = [];

    public function __construct(SimpleXMLElement $item, CategoryFilter $filter)
    {
        parent::__construct($item);
        $this->addSubItems($item, $filter);
    }

    /**
     * Items might have sub items. Sub items may only be set for subcategories.
     */
    private function addSubItems(SimpleXMLElement $response, CategoryFilter $filter): void
    {
        if (isset($response->items)) {
            foreach ($response->items->children() as $item) {
                $itemName = ResponseHelper::getStringProperty($item, 'name');
                $categoryItem = Item::getInstance($filter, $item);
                if (!$categoryItem instanceof CategoryItem) {
                    continue;
                }

                $this->items[$itemName] = $categoryItem;
            }
        }
    }

    /**
     * @return CategoryItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
