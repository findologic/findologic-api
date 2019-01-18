<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use Exception;
use FINDOLOGIC\Helpers\ResponseHelper;
use SimpleXMLElement;

class Product
{
    /** @var string $id */
    private $id;

    /** @var float $relevance */
    private $relevance;

    /** @var int $direct Note: This value is always zero but is kept for legacy reasons. */
    private $direct;

    /** @var array $properties */
    private $properties = [];

    /**
     * Product constructor.
     * @param SimpleXMLElement $result
     */
    public function __construct($result)
    {
        $attributes = $result->attributes();
        $this->id = ResponseHelper::getProperty($attributes, 'id', 'string');
        $this->relevance =  ResponseHelper::getProperty($attributes, 'relevance', 'float');
        $this->direct =  ResponseHelper::getProperty($attributes, 'direct', 'int', true);

        if (isset($result->properties)) {
            foreach ($result->properties->children() as $property) {
                $propertyName =  ResponseHelper::getProperty($property->attributes(), 'name', 'string');
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
     * @return int
     */
    public function getDirect()
    {
        return $this->direct;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
