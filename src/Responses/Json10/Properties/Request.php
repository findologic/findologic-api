<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Request
{
    private ?string $query;
    private int $first;
    private int $count;
    private string $serviceId;
    private ?string $usergroup;
    private Order $order;

    public function __construct(array $request)
    {
        $this->query = ResponseHelper::getStringProperty($request, 'query');
        $this->first = ResponseHelper::getIntProperty($request, 'first', true);
        $this->count = ResponseHelper::getIntProperty($request, 'count');
        $this->serviceId = ResponseHelper::getStringProperty($request, 'serviceId');
        $this->usergroup = ResponseHelper::getStringProperty($request, 'usergroup');
        $this->order = new Order($request['order']);
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function getFirst(): int
    {
        return $this->first;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getUsergroup(): ?string
    {
        return $this->usergroup;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }
}
