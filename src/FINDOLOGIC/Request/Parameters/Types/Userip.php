<?php

namespace FINDOLOGIC\Request\Parameters\Types;

use FINDOLOGIC\Request\Parameters\ParameterValidator;

class Userip
{
    const PARAM_KEY = 'userip';

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
        ParameterValidator::validateUserip($value);
        $this->value = $value;
    }
}
