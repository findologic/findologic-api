<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class BaseItem
{
    /** @var string */
    private $id;

    /** @var float */
    private $score;

    /** @var ?string */
    private $url;

    /** @var ?string */
    private $name;

    /** @var string[] */
    private $ordernumbers = [];

    /** @var ?string */
    private $matchingOrdernumber;

    /** @var float */
    private $price;

    /** @var ?string */
    private $summary;

    /** @var array<string, array<string>> */
    private $attributes = [];

    /** @var array<string, string> */
    private $properties = [];

    /** @var ?string */
    private $imageUrl;

    public function __construct(array $item)
    {
        $this->id = ResponseHelper::getStringProperty($item, 'id');
        $this->score = ResponseHelper::getFloatProperty($item, 'score', true);
        $this->url = ResponseHelper::getStringProperty($item, 'url');
        $this->name = ResponseHelper::getStringProperty($item, 'name');

        if (isset($item['ordernumbers'])) {
            foreach ($item['ordernumbers'] as $ordernumber) {
                $this->ordernumbers[] = $ordernumber;
            }
        }
        $this->matchingOrdernumber = ResponseHelper::getStringProperty($item, 'matchingOrdernumber');
        $this->price = ResponseHelper::getFloatProperty($item, 'price', true);
        $this->summary = ResponseHelper::getStringProperty($item, 'summary');

        if (isset($item['attributes'])) {
            $this->attributes = $item['attributes'];
        }
        if (isset($item['properties'])) {
            $this->properties = $item['properties'];
        }

        $this->imageUrl = ResponseHelper::getStringProperty($item, 'imageUrl');
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
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @return string[]
     */
    public function getOrdernumbers()
    {
        return $this->ordernumbers;
    }

    /**
     * @return string|null
     */
    public function getMatchingOrdernumber()
    {
        return $this->matchingOrdernumber;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string|null
     */
    public function getSummary()
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
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Returns the attribute/filter by the given name. If the attribute does not exist, the default may be returned.
     *
     * @param string $attributeName
     * @param mixed|null $default
     * @return array|mixed
     */
    public function getAttribute($attributeName, $default = null)
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
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Returns the property by the given name. If the property does not exist, the default will be returned.
     *
     * @param string $propertyName
     * @param mixed|null $default
     * @return string|mixed
     */
    public function getProperty($propertyName, $default = null)
    {
        if (!isset($this->properties[$propertyName])) {
            return $default;
        }

        return $this->properties[$propertyName];
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }
}
