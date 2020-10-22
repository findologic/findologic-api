<?php

namespace FINDOLOGIC\Api\Validators\Rules;

class RegexRule extends Rule
{
    public function __construct($parameterName, $params = null)
    {
        parent::__construct($parameterName, 'regex', $params);
    }
}
