<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Definitions\OrderType;
use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Order
{
    private string $field;
    private bool $relevanceBased;
    private string $direction;

    public function __construct(array $order)
    {
        $this->field = ResponseHelper::getStringProperty($order, 'field');
        $this->relevanceBased = ResponseHelper::getBoolProperty($order, 'relevanceBased');
        $this->direction = ResponseHelper::getStringProperty($order, 'direction');
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function isRelevanceBased(): bool
    {
        return $this->relevanceBased;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * May return "salesfrequency dynamic DESC" or similar.
     */
    public function __toString(): string
    {
        return OrderType::buildOrder($this->field, $this->relevanceBased, $this->direction);
    }
}
