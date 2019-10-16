<?php

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Product
{
    /** @var string $id */
    private $id;

    /** @var float $relevance */
    private $relevance;

    /** @var array $properties */
    private $properties = [];

    public function __construct(SimpleXMLElement $response)
    {
        $attributes = $response->attributes();
        $this->id = ResponseHelper::getStringProperty($attributes, 'id');
        $this->relevance =  ResponseHelper::getFloatProperty($attributes, 'relevance');

        if (isset($response->properties)) {
            foreach ($response->properties->children() as $property) {
                $propertyName =  ResponseHelper::getStringProperty($property->attributes(), 'name');
                $propertyValue = (string)$property;
                $this->properties[$propertyName] = $propertyValue;
            }
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getRelevance()
    {
        return $this->relevance;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
