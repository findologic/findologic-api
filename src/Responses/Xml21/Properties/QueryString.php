<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class QueryString
{
    /** @var string $value */
    private $value;

    /** @var string|null $type */
    private $type;

    public function __construct(SimpleXMLElement $response)
    {
        $this->value = (string)$response;
        $this->type = ResponseHelper::getStringProperty($response->attributes(), 'type');
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        return $this->type;
    }
}
