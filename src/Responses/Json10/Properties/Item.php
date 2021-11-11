<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Definitions\Defaults;
use FINDOLOGIC\Api\Helpers\ResponseHelper;

/**
 * Holds data of an item/product.
 */
class Item
{
    private string $id;
    private float $score;
    private string $url;
    private string $name;
    private string $highlightedName;
    /** @var string[] */
    private array $ordernumbers = [];
    private ?string $matchingOrdernumber;
    private float $price;
    private ?string $summary;
    /** @var array<string, array<string>> */
    private array $attributes = [];
    /** @var array<string, string> */
    private array $properties = [];
    private ?string $productPlacement;
    /** @var string[] */
    private array $pushRules = [];
    private string $imageUrl;

    /**
     * @param array<string, array<string, array<string>>|array<string, string>|string[]|string|float|null> $item
     */
    public function __construct(array $item)
    {
        $this->id = ResponseHelper::getStringProperty($item, 'id') ?? Defaults::EMPTY;
        $this->score = ResponseHelper::getFloatProperty($item, 'score', true) ?? 0.0;
        $this->url = ResponseHelper::getStringProperty($item, 'url') ?? Defaults::EMPTY;
        $this->name = ResponseHelper::getStringProperty($item, 'name') ?? Defaults::EMPTY;
        $this->highlightedName = ResponseHelper::getStringProperty($item, 'highlightedName') ?? Defaults::EMPTY;

        if (isset($item['ordernumbers']) && is_array($item['ordernumbers'])) {
            foreach ($item['ordernumbers'] as $ordernumber) {
                if (!is_string($ordernumber)) {
                    continue;
                }

                $this->ordernumbers[] = $ordernumber;
            }
        }
        $this->matchingOrdernumber = ResponseHelper::getStringProperty($item, 'matchingOrdernumber');
        $this->price = ResponseHelper::getFloatProperty($item, 'price', true) ?? 0.0;
        $this->summary = ResponseHelper::getStringProperty($item, 'summary');

        if (isset($item['attributes']) && is_array($item['attributes'])) {
            foreach ($item['attributes'] as $key => $attribute) {
                if (!is_array($attribute)) {
                    continue;
                }

                $this->attributes[(string)$key] = $attribute;
            }
        }
        if (isset($item['properties']) && is_array($item['properties'])) {
            foreach ($item['properties'] as $key => $value) {
                if (!is_string($value)) {
                    continue;
                }

                $this->properties[(string)$key] = $value;
            }
        }
        if (isset($item['pushRules']) && is_array($item['pushRules'])) {
            foreach ($item['pushRules'] as $pushRule) {
                if (!is_string($pushRule)) {
                    continue;
                }

                $this->pushRules[] = $pushRule;
            }
        }

        $this->productPlacement = ResponseHelper::getStringProperty($item, 'productPlacement');
        $this->imageUrl = ResponseHelper::getStringProperty($item, 'imageUrl') ?? Defaults::EMPTY;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHighlightedName(): string
    {
        return $this->highlightedName;
    }

    /**
     * @return string[]
     */
    public function getOrdernumbers(): array
    {
        return $this->ordernumbers;
    }

    public function getMatchingOrdernumber(): ?string
    {
        return $this->matchingOrdernumber;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * Returns filters that are assigned to a product e.g.
     * ```
     * [
     *     'filter-name' => [
     *         'value1',
     *         'value2',
     *         // ...
     *     ]
     * ];
     * ```
     *
     * An attribute may only be returned when explicitly adding it to the request with
     * `$searchRequest->addOutputAttrib($filterName)`.
     *
     * @return array<string, array<string>>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Returns the attribute/filter by the given name. If the attribute does not exist, the default may be returned.
     *
     * @param mixed|null $default
     * @return array|mixed
     */
    public function getAttribute(string $attributeName, $default = null)
    {
        if (!isset($this->attributes[$attributeName])) {
            return $default;
        }

        return $this->attributes[$attributeName];
    }

    /**
     * Returns properties that are assigned to a product e.g.
     * ```
     * [
     *     'property-1' => 'some-value',
     *     'property-2' => 'some-value',
     *     // ...
     * ];
     * ```
     *
     * A property may only be returned when explicitly adding it to the request with
     * `$searchRequest->addProperty($propertyName)`.
     *
     * @return array<string, string>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * Returns the property by the given name. If the property does not exist, the default will be returned.
     *
     * @param mixed|null $default
     * @return string|mixed
     */
    public function getProperty(string $propertyName, $default = null)
    {
        if (!isset($this->properties[$propertyName])) {
            return $default;
        }

        return $this->properties[$propertyName];
    }

    public function getProductPlacement(): ?string
    {
        return $this->productPlacement;
    }

    /**
     * @return string[]
     */
    public function getPushRules(): array
    {
        return $this->pushRules;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}
