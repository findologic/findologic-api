<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use SimpleXMLElement;

class Filter
{
    /** @var string $name */
    private $name;

    /** @var string $display */
    private $display;

    /** @var string $select */
    private $select;

    /** @var int $selectedItems */
    private $selectedItems;

    /** @var string $type */
    private $type;

    /** @var Attributes $attributes */
    private $attributes;

    /** @var Item[] $items */
    private $items;

    /**
     * Filter constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
    {
        $this->name = (string)$response->name;
        $this->display = (string)$response->display;
        $this->select = (string)$response->select;
        $this->selectedItems = (int)$response->selectedItems;
        $this->type = (string)$response->type;

        if ($response->attributes) {
            $this->attributes = new Attributes($response->attributes);
        }

        foreach ($response->items->children() as $item) {
            $itemName = (string)$item->name;
            $this->items[$itemName] = new Item($item);
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
}
