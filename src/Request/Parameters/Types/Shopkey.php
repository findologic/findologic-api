<?php

namespace Request\Parameters\Types;

use Request\ParameterValidator\ParameterValidator;

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