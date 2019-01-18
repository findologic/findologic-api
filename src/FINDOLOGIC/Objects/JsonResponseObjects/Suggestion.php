<?php

namespace FINDOLOGIC\Objects\JsonResponseObjects;

use FINDOLOGIC\Helpers\ResponseHelper;

class Suggestion
{
    /** @var string $label */
    private $label;

    /** @var string $block */
    private $block;

    /** @var string $frequency */
    private $frequency;

    /** @var string|null $imageUrl */
    private $imageUrl;

    /** @var float|null $price */
    private $price;

    /** @var string|null $identifier */
    private $identifier;

    /** @var float|null $basePrice */
    private $basePrice;

    /** @var string|null $basePriceUnit */
    private $basePriceUnit;

    /** @var string|null $basePriceUnit */
    private $url;

    /** @var string|null $ordernumber */
    private $ordernumber;

    public function __construct($response)
    {
        $this->label = ResponseHelper::getProperty($response, 'label', 'string');
        $this->block = ResponseHelper::getProperty($response, 'block', 'string');
        $this->frequency = ResponseHelper::getProperty($response, 'frequency', 'string');
        $this->imageUrl = ResponseHelper::getProperty($response, 'imageUrl', 'string');
        $this->price = ResponseHelper::getProperty($response, 'price', 'float');
        $this->identifier = ResponseHelper::getProperty($response, 'identifier', 'string');
        $this->basePrice = ResponseHelper::getProperty($response, 'basePrice', 'float');
        $this->basePriceUnit = ResponseHelper::getProperty($response, 'basePriceUnit', 'string');
        $this->url = ResponseHelper::getProperty($response, 'url', 'string');
        $this->ordernumber = ResponseHelper::getProperty($response, 'ordernumber', 'string');
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @return string
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @return string|null
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @return float|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string|null
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return float|null
     */
    public function getBasePrice()
    {
        return $this->basePrice;
    }

    /**
     * @return string|null
     */
    public function getBasePriceUnit()
    {
        return $this->basePriceUnit;
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getOrdernumber()
    {
        return $this->ordernumber;
    }
}
