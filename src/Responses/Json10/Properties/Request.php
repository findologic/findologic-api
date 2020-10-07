<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Request
{
    /** @var string|null */
    private $query;

    /** @var int */
    private $first;

    /** @var int */
    private $count;

    /** @var string */
    private $serviceId;

    /** @var string|null */
    private $usergroup;

    /** @var Order */
    private $order;

    public function __construct(array $request)
    {
        $this->query = ResponseHelper::getStringProperty($request, 'query');
        $this->first = ResponseHelper::getIntProperty($request, 'first', true);
        $this->count = ResponseHelper::getIntProperty($request, 'count');
        $this->serviceId = ResponseHelper::getStringProperty($request, 'serviceId');
        $this->usergroup = ResponseHelper::getStringProperty($request, 'usergroup');
        $this->order = new Order($request['order']);
    }

    /**
     * @return string|null
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
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }
}
