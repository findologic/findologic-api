<?php

namespace FINDOLOGIC\Api\Requests\Item\Changes;

class PriceChange extends Change
{
    /** @var float */
    private $price = 0.0;

    public function getKey()
    {
        return 'price';
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->getPrice();
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }
}
