<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Variant
{
    /** @var string */
    private $name;

    public function __construct(array $variant)
    {
        $this->name = ResponseHelper::getStringProperty($variant, 'name');
    }
}
