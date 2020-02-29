<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Property
{
    /** @var string */
    private $name;

    /** @var string */
    private $value;

    public function __construct(array $property)
    {
        $this->name = ResponseHelper::getStringProperty($property, 'name');
        $this->value = ResponseHelper::getStringProperty($property, 'value');
    }
}
