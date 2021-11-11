<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Limit
{
    private int $first;
    private ?int $count;

    public function __construct(SimpleXMLElement $response)
    {
        $this->first = ResponseHelper::getIntProperty($response, 'first', true) ?? 0;
        $this->count = ResponseHelper::getIntProperty($response, 'count', true);
    }

    public function getFirst(): int
    {
        return $this->first;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }
}
