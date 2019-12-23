<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

class Result
{
    /** @var Metadata */
    private $metadata;

    /** @var Variant */
    private $variant;

    /** @var Item[] */
    private $items = [];
}
