<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Responses\Json10\Properties\Filter\Filter;

class Result
{
    /** @var Metadata */
    private $metadata;

    /** @var Item[] */
    private $items = [];

    /** @var Variant */
    private $variant;

    /** @var Filter[] */
    private $mainFilters;

    /** @var Filter[] */
    private $otherFilters;

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
}
