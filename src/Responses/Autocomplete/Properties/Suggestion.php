<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Autocomplete\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Suggestion
{
    private string $label;
    private string $block;
    private string $frequency;
    private ?string $imageUrl;
    private ?float $price;
    private ?string $identifier;
    private ?float $basePrice;
    private ?string $basePriceUnit;
    private ?string $url;
    private ?string $ordernumber;

    public function __construct(array $response)
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

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getBlock(): string
    {
        return $this->block;
    }

    public function getFrequency(): string
    {
        return $this->frequency;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getBasePrice(): ?float
    {
        return $this->basePrice;
    }

    public function getBasePriceUnit(): ?string
    {
        return $this->basePriceUnit;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getOrdernumber(): ?string
    {
        return $this->ordernumber;
    }
}
