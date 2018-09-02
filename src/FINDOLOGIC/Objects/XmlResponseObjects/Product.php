<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use Exception;
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
        $this->id = (string)$attributes->id;
        $this->relevance = (float)$attributes->relevance;
        $this->direct = (int)$attributes->direct;

        try {
            foreach ($result->properties->children() as $property) {
                $propertyName = (string)$property->attributes()->name;
                $propertyValue = (string)$property;
                $this->properties[$propertyName] = $propertyValue;
            }
        } catch (Exception $e) {
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
