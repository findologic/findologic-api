<?php

namespace FINDOLOGIC\Request\Parameters\Types;

use FINDOLOGIC\Request\ParameterValidator\ParameterValidator;

class Shopkey
{
    const PARAM_KEY = 'shopkey';

    public $value;
    public $required = true;

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        ParameterValidator::validateShopkey($value);
        $this->value = $value;
    }
}