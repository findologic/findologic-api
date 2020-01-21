<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\Item;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use FINDOLOGIC\Api\Responses\Xml21\Properties\Filter\CategoryFilter;
use SimpleXMLElement;

class CategoryItem extends Item
{
    /** @var CategoryItem[] $items */
    protected $items = [];

    public function __construct(SimpleXMLElement $item, CategoryFilter $filter)
    {
        parent::__construct($item);
        $this->addSubItems($item, $filter);
    }

    /**
     * Items might have sub items. Sub items may only be set for subcategories.
     *
     * @param SimpleXMLElement $response
     * @param CategoryFilter $filter
     */
    private function addSubItems(SimpleXMLElement $response, CategoryFilter $filter)
    {
        if (isset($response->items)) {
            foreach ($response->items->children() as $item) {
                $itemName = ResponseHelper::getStringProperty($item, 'name');
                $this->items[$itemName] = Item::getInstance($filter, $item);
            }
        }
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
