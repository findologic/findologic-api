<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Item
{
    /** @var string */
    private $id;

    /** @var float */
    private $score;

    /** @var string */
    private $url;

    /** @var string */
    private $name;

    /** @var string */
    private $highlightedName;

    /** @var string[] */
    private $ordernumbers;

    /** @var string */
    private $summary;

    /** @var float */
    private $price;

    /** @var PropertyCollection */
    private $properties;

    /** @var string */
    private $productPlacement;

    /** @var string[] */
    private $pushRules;

    /** @var AttributeCollection */
    private $attributes;

    public function __construct(array $item)
    {
        $this->id = ResponseHelper::getStringProperty($item, 'id');
        $this->score = ResponseHelper::getFloatProperty($item, 'score', true);
        $this->url = ResponseHelper::getStringProperty($item, 'url');
        $this->name = ResponseHelper::getStringProperty($item, 'name');
        $this->highlightedName = ResponseHelper::getStringProperty($item, 'highlightedName');
        $this->setOrdernumbers($item);
        $this->summary = ResponseHelper::getStringProperty($item, 'summary');
        $this->price = ResponseHelper::getFloatProperty($item, 'price', true);
        $this->setProperties($item);
        $this->productPlacement = ResponseHelper::getStringProperty($item, 'productPlacement');
        $this->setPushRules($item);
        $this->setAttributes($item);
    }

    private function setOrdernumbers(array $item)
    {
        $this->ordernumbers = isset($item['ordernumbers']) ? $item['ordernumbers'] : [];
    }

    private function setProperties(array $item)
    {
        $properties = isset($item['properties']) ? $item['properties'] : [];
        $this->properties = new PropertyCollection($properties);
    }

    private function setPushRules(array $item)
    {
        $this->pushRules = isset($item['pushRules']) ? $item['pushRules'] : [];
    }

    private function setAttributes(array $item)
    {
        $attributes = isset($item['attributes']) ? $item['attributes'] : [];
        $this->attributes = new AttributeCollection($attributes);
    }
}
