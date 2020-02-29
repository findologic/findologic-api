<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

/**
 * Holds data of an item/product.
 */
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
    private $ordernumbers = [];

    /** @var string|null */
    private $matchingOrdernumber;

    /** @var float */
    private $price;

    /** @var string|null */
    private $summary;

    /** @var Attribute[] */
    private $attributes = [];

    /** @var Property[] */
    private $properties = [];

    /** @var string|null */
    private $productPlacement;

    /** @var string[]|null */
    private $pushRules;

    /** @var string */
    private $imageUrl;

    public function __construct(array $item)
    {
        $this->id = ResponseHelper::getStringProperty($item, 'id');
        $this->score = ResponseHelper::getFloatProperty($item, 'score', true);
        $this->url = ResponseHelper::getStringProperty($item, 'url');
        $this->name = ResponseHelper::getStringProperty($item, 'name');
        $this->highlightedName = ResponseHelper::getStringProperty($item, 'highlightedName');

        if (isset($item['ordernumbers'])) {
            foreach ($item['ordernumbers'] as $ordernumber) {
                $this->ordernumbers[] = $ordernumber;
            }
        }
        $this->matchingOrdernumber = ResponseHelper::getStringProperty($item, 'matchingOrdernumber');
        $this->price = ResponseHelper::getFloatProperty($item, 'price', true);
        $this->summary = ResponseHelper::getStringProperty($item, 'summary');

        if (isset($item['attributes'])) {
            foreach ($item['attributes'] as $attribute) {
                $this->attributes[] = new Attribute($attribute);
            }
        }
        if (isset($item['properties'])) {
            foreach ($item['properties'] as $property) {
                $this->properties[] = new Property($property);
            }
        }
        if (isset($item['pushRules'])) {
            $this->pushRules = $item['pushRules'];
        }

        $this->productPlacement = ResponseHelper::getStringProperty($item, 'productPlacement');
        $this->imageUrl = ResponseHelper::getStringProperty($item, 'imageUrl');
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
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
    public function getHighlightedName()
    {
        return $this->highlightedName;
    }

    /**
     * @return string[]
     */
    public function getOrdernumbers()
    {
        return $this->ordernumbers;
    }

    /**
     * @return string|null
     */
    public function getMatchingOrdernumber()
    {
        return $this->matchingOrdernumber;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string|null
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @return Attribute[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return Property[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return string|null
     */
    public function getProductPlacement()
    {
        return $this->productPlacement;
    }

    /**
     * @return string[]|null
     */
    public function getPushRules()
    {
        return $this->pushRules;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }
}
