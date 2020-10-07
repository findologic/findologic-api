<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

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
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * May return "salesfrequency dynamic DESC" or similar.
     *
     * @return string
     */
    public function __toString()
    {
        $dynamic = $this->relevanceBased ? 'dynamic ' : '';

        return sprintf('%s %s%s', $this->field, $dynamic, $this->direction);
    }
}
