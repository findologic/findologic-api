<?php

namespace FINDOLOGIC\Api\Responses\Xml20\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

/**
 * @deprecated Use XML 2.1 instead. This class will be removed with version v1.0.0-rc.1.
 */
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
        $this->id = ResponseHelper::getStringProperty($attributes, 'id');
        $this->relevance =  ResponseHelper::getFloatProperty($attributes, 'relevance');
        $this->direct =  ResponseHelper::getIntProperty($attributes, 'direct', true);

        if (isset($result->properties)) {
            foreach ($result->properties->children() as $property) {
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
