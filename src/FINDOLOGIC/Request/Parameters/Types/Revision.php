<?php

namespace FINDOLOGIC\Request\Parameters\Types;

use FINDOLOGIC\Request\Parameters\ParameterValidator;

class Revision
{
    const PARAM_KEY = 'revision';

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
        ParameterValidator::validateRevision($value);
        $this->value = $value;
    }
}
