<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Filter;

class Result
{
    private Metadata $metadata;
    /** @var Item[] */
    private array $items = [];
    private Variant $variant;
    /** @var Filter[] */
    private array $mainFilters;
    /** @var Filter[] */
    private array $otherFilters;

    public function __construct(array $result)
    {
        $this->metadata = new Metadata($result['metadata']);
        foreach ($result['items'] as $item) {
            $this->items[] = new Item($item);
        }

        $this->variant = new Variant($result['variant']);
        foreach ($result['filters']['main'] as $mainFilter) {
            $this->mainFilters[] = Filter::getInstance($mainFilter);
        }
        foreach ($result['filters']['other'] as $otherFilter) {
            $this->otherFilters[] = Filter::getInstance($otherFilter);
        }
    }

    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getVariant(): Variant
    {
        return $this->variant;
    }

    /**
     * @return Filter[]
     */
    public function getMainFilters(): array
    {
        return $this->mainFilters;
    }

    /**
     * @return Filter[]
     */
    public function getOtherFilters(): array
    {
        return $this->otherFilters;
    }
}
