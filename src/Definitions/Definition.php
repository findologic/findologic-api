<?php

namespace FINDOLOGIC\Api\Definitions;

use ReflectionClass;

abstract class Definition
{
    public static function getConstants()
    {
        /** @noinspection PhpUnhandledExceptionInspection Current class will always exist. */
        $reflectionClass = new ReflectionClass(get_called_class());
        return $reflectionClass->getConstants();
    }
}
