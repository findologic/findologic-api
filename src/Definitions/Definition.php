<?php

namespace FINDOLOGIC\Api\Definitions;

use ReflectionClass;

abstract class Definition
{
    /**
     * Returns a key value pair of defined constants.
     *
     * @return array
     */
    public static function getConstants()
    {
        return (new ReflectionClass(get_called_class()))->getConstants();
    }
}
