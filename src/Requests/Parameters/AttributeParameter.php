<?php

namespace FINDOLOGIC\Api\Requests\Parameters;

class AttributeParameter extends Parameter
{
    /**
     * @param string $name
     * @param array $value
     */
    public function __construct($name, array $value)
    {
        parent::__construct($name, $value);
    }
}
