<?php

namespace FINDOLOGIC\Api\Requests\Parameters;

abstract class SimpleParameter extends Parameter
{
    /**
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        parent::__construct($name, $value);
    }
}
