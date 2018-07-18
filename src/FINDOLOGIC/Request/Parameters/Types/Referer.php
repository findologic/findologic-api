<?php

namespace FINDOLOGIC\Request\Parameters\Types;

use FINDOLOGIC\Request\Parameters\ParameterValidator;

class Referer
{
    const PARAM_KEY = 'referer';

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
        ParameterValidator::validateReferer($value);
        $this->value = $value;
    }
}
