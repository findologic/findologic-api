<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Definitions\Defaults;
use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class Product
{
    private string $id;
    private float $relevance;
    /** @var array<string, string> */
    private array $properties = [];

    public function __construct(SimpleXMLElement $response)
    {
        $attributes = $response->attributes();
        $this->id = ResponseHelper::getStringProperty($attributes, 'id') ?? Defaults::EMPTY;
        $this->relevance =  ResponseHelper::getFloatProperty($attributes, 'relevance') ?? 0.0;

        if (isset($response->properties)) {
            foreach ($response->properties->children() as $property) {
                $propertyName =  ResponseHelper::getStringProperty($property->attributes(), 'name');
                $propertyValue = (string)$property;
                $this->properties[$propertyName] = $propertyValue;
            }
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRelevance(): float
    {
        return $this->relevance;
    }

    /**
     * @return array<string, string>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
