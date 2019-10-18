<?php

namespace FINDOLOGIC\Api\Responses\Xml20\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

/**
 * @deprecated Use XML 2.1 instead. This class will be removed with version v1.0.0-rc.1.
 */
class Filter
{
    /** @var string $name */
    private $name;

    /** @var string $display */
    private $display;

    /** @var string $select */
    private $select;

    /** @var int|null $selectedItems */
    private $selectedItems;

    /** @var string $type */
    private $type;

    /** @var Attributes $attributes */
    private $attributes;

    /** @var Item[] $items */
    private $items;

    /** @var bool $hasItems */
    private $hasItems = false;

    /** @var int $itemAmount */
    private $itemAmount = 0;

    /**
     * Filter constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
    {
        $this->name =  ResponseHelper::getStringProperty($response, 'name');
        $this->display =  ResponseHelper::getStringProperty($response, 'display');
        $this->select =  ResponseHelper::getStringProperty($response, 'select');
        $this->selectedItems = ResponseHelper::getIntProperty($response, 'selectedItems');
        $this->type = ResponseHelper::getStringProperty($response, 'type');

        if ($response->attributes) {
            $this->attributes = new Attributes($response->attributes);
        }

        if ($response->items) {
            // Get the first <items> element, containing all <item> elements.
            foreach ($response->items[0] as $item) {
                $itemName = ResponseHelper::getStringProperty($item, 'name');
                $this->items[$itemName] = new Item($item);
                $this->hasItems = true;
                $this->itemAmount++;
            }
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * @return int
     */
    public function getItemAmount()
    {
        return $this->itemAmount;
    }

    /**
     * @return bool
     */
    public function hasItems()
    {
        return $this->hasItems;
    }
}
