<?php

namespace FINDOLOGIC\Api\ResponseObjects\Json\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

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
        $this->label = ResponseHelper::getStringProperty($response, 'label');
        $this->block = ResponseHelper::getStringProperty($response, 'block');
        $this->frequency = ResponseHelper::getStringProperty($response, 'frequency');
        $this->imageUrl = ResponseHelper::getStringProperty($response, 'imageUrl');
        $this->price = ResponseHelper::getFloatProperty($response, 'price');
        $this->identifier = ResponseHelper::getStringProperty($response, 'identifier');
        $this->basePrice = ResponseHelper::getFloatProperty($response, 'basePrice');
        $this->basePriceUnit = ResponseHelper::getStringProperty($response, 'basePriceUnit');
        $this->url = ResponseHelper::getStringProperty($response, 'url');
        $this->ordernumber = ResponseHelper::getStringProperty($response, 'ordernumber');
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
