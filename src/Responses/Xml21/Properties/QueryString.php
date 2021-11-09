<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class QueryString
{
    private string $value;
    private ?string $type;

    public function __construct(SimpleXMLElement $response)
    {
        $this->value = (string)$response;
        $this->type = ResponseHelper::getStringProperty($response->attributes(), 'type');
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
}
