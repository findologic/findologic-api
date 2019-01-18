<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use Exception;
use FINDOLOGIC\Helpers\ResponseHelper;
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
        $this->name = ResponseHelper::getProperty($response, 'name', 'string');
        $this->display = ResponseHelper::getProperty($response, 'display', 'string');
        $this->weight = ResponseHelper::getProperty($response, 'weight', 'float');
        $this->frequency = ResponseHelper::getProperty($response, 'frequency', 'int');
        $this->select = ResponseHelper::getProperty($response, 'select', 'string');
        $this->image = ResponseHelper::getProperty($response, 'image', 'string');
        $this->color = ResponseHelper::getProperty($response, 'color', 'string');
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
                $itemName = ResponseHelper::getProperty($item, 'name', 'string');
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
