<?php

namespace FINDOLOGIC\Request\Parameters\Types;

use FINDOLOGIC\Request\ParameterValidator\ParameterValidator;

class Shopurl
{
    const PARAM_KEY = 'shopurl';

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
        ParameterValidator::validateShopurl($value);
        $this->value = $value;
    }
}
