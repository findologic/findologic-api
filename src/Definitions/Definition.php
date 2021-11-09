<?php

namespace FINDOLOGIC\Api\Definitions;

use ReflectionClass;

abstract class Definition
{
    /**
     * Returns a key value pair of defined constants.
     */
    public static function getConstants(): array
    {
        return (new ReflectionClass(get_called_class()))->getConstants();
    }
}
