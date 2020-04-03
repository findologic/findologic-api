<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use BadMethodCallException;
use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Request
{
    /** @var string */
    private $query;

    /** @var int */
    private $first;

    /** @var int */
    private $count;

    /** @var string */
    private $serviceId;

    /** @var string|null */
    private $usergroup;

    /** @var string|null */
    private $userId;

    /** @var Order */
    private $order;

    public function __construct(array $request)
    {
        $this->query = ResponseHelper::getStringProperty($request, 'query');
        $this->first = ResponseHelper::getIntProperty($request, 'first', true);
        $this->count = ResponseHelper::getIntProperty($request, 'count', true);
        $this->serviceId = ResponseHelper::getStringProperty($request, 'serviceId');
        $this->usergroup = ResponseHelper::getStringProperty($request, 'usergroup');
        $this->userId = ResponseHelper::getStringProperty($request, 'userId');
        $this->order = ResponseHelper::castTo($request, 'order', Order::class);
    }

    /**
     * @internal Call Filter::isSelected() instead.
     * @deprecated Call Filter::isSelected() instead.
     */
    public function getSelectedFilters()
    {
        throw new BadMethodCallException('Use Filter::isSelected() instead.');
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return int
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return string
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * @return string|null
     */
    public function getUsergroup()
    {
        return $this->usergroup;
    }

    /**
     * @return string|null
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }
}
