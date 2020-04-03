<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Definitions\OrderType;
use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Order
{
    /** @var string */
    private $field;

    /** @var bool */
    private $relevanceBased;

    /** @var string */
    private $direction;

    public function __construct(array $order)
    {
        $this->field = ResponseHelper::getStringProperty($order, 'field');
        $this->relevanceBased = ResponseHelper::getBoolProperty($order, 'relevanceBased');
        $this->direction = ResponseHelper::getStringProperty($order, 'direction');
    }

    /**
     * @return string
     * @see OrderType for available fields.
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return bool
     */
    public function isRelevanceBased()
    {
        return $this->relevanceBased;
    }

    /**
     * @return string
     * @see OrderType for available directions.
     */
    public function getDirection()
    {
        return $this->direction;
    }
}
