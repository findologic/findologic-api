<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use Exception;
use SimpleXMLElement;

class QueryString
{
    /** @var string $value */
    private $value;

    /** @var string|null $type */
    private $type;

    /**
     * QueryString constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
    {
        $this->value = (string)$response;
        $this->type = (string)$response->attributes()->type;
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
