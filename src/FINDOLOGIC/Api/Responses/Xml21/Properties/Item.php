<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Item
{
    /** @var string $name */
    private $name;

    /** @var float|null $weight */
    private $weight;

    /** @var int|null $frequency */
    private $frequency;

    /** @var Item[]|null $items */
    private $items;

    /** @var string|null $image */
    private $image;

    /** @var string|null $color */
    private $color;

    /** @var Range|null $parameters */
    private $parameters;

    /** @var bool|null $selected */
    private $selected;

    public function __construct(SimpleXMLElement $response)
    {
        $this->name = ResponseHelper::getStringProperty($response, 'name');
        $this->weight = ResponseHelper::getFloatProperty($response, 'weight');
        $this->frequency = ResponseHelper::getIntProperty($response, 'frequency');
        $this->image = ResponseHelper::getStringProperty($response, 'image');
        $this->color = ResponseHelper::getStringProperty($response, 'color');
        $this->selected = ResponseHelper::getBoolProperty($response->attributes(), 'selected');
        $this->addSubItems($response);

        if ($response->parameters) {
            $this->parameters = new Range($response->parameters);
        }
    }

    /**
     * Items might have sub items. Sub items may only be set for subcategories.
     * @param SimpleXMLElement $response
     */
    private function addSubItems(SimpleXMLElement $response)
    {
        if (isset($response->items)) {
            foreach ($response->items->children() as $item) {
                $itemName = ResponseHelper::getStringProperty($item, 'name');
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
     * @return Item[]|null
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return string|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string|null
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return Range|null
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return bool|null
     */
    public function getSelected()
    {
        return $this->selected;
    }
}
