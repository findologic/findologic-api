<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use Exception;
use SimpleXMLElement;

class Item
{
    /** @var string $name */
    private $name;

    /** @var string $display */
    private $display;

    /** @var float $weight */
    private $weight;

    /** @var int $frequency */
    private $frequency;

    /** @var string $select */
    private $select;

    /** @var string $image */
    private $image;

    /** @var string $color */
    private $color;

    /** @var Item[] $items */
    private $items;

    /**
     * Item constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
    {
        $this->name = (string)$response->name;
        $this->display = (string)$response->display;
        $this->weight = (float)$response->weight;
        $this->frequency = (int)$response->frequency;
        $this->select = (string)$response->select;
        $this->image = (string)$response->image;
        $this->color = (string)$response->color;
        $this->addSubItems($response);
    }

    /**
     * Items might have sub items. Sub items may only be set for subcategories.
     * @param SimpleXMLElement $response
     */
    private function addSubItems($response)
    {
        if (isset($response->items)) {
            foreach ($response->items->children() as $item) {
                $itemName = (string)$item->name;
                $this->items[$itemName] = new Item($item);
            }
        } else {
            $this->items = null;
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
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return int
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @return string
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
