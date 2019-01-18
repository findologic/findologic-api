<?php

namespace FINDOLOGIC\Helpers;

use InvalidArgumentException;

class ResponseHelper
{
    /**
     * Gets a property from an object.
     *
     * @param mixed $obj
     * @param string $property
     * @param null|string $type Convert value to another type. Optional.
     * @param bool $emptyValueIsAllowed If true, values that are 0 or 0.0 are allowed.
     *
     * @return mixed|null Returns the property value or null if it does not exist.
     */
    public static function getProperty($obj, $property, $type = null, $emptyValueIsAllowed = false)
    {
        if (!property_exists($obj, $property)) {
            return null;
        }

        if ($type === null) {
            return $obj->{$property};
        }

        $typedProperty = $obj->{$property};
        if (!settype($typedProperty, $type)) {
            throw new InvalidArgumentException('Unknown or invalid type submitted.');
        }

        // Check for empty after the property has been casted to submitted the type.
        if (!$emptyValueIsAllowed && self::isEmpty($typedProperty)) {
            return null;
        }

        return $typedProperty;
    }

    /**
     * Checks if a variable is empty. Please take note that this function isn't like the PHP function, so a function
     * will more likely not be empty when you expect it to be.
     *
     * @param mixed $v
     *
     * @return bool
     */
    private static function isEmpty($v)
    {
        return ($v === '' || $v === 0 || $v === 0.0);
    }
}
