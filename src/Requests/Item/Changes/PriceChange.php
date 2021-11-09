<?php

namespace FINDOLOGIC\Api\Requests\Item\Changes;

class PriceChange extends Change
{
    private float $price = 0.0;

    public function getKey(): string
    {
        return 'price';
    }

    public function getValue(): float
    {
        return $this->getPrice();
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
