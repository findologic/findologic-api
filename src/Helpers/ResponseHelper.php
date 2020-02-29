<?php

namespace FINDOLOGIC\Api\Helpers;

class ResponseHelper
{
    const TYPE_STRING = 'string',
          TYPE_INT = 'int',
          TYPE_FLOAT = 'float',
          TYPE_BOOL = 'bool';

    /**
     * Gets a property from an object and converts it to a string.
     *
     * @param object|array $obj
     * @param string $property
     * @param bool $allowEmptyValues If true, values that are 0 or 0.0 are allowed.
     *
     * @return string|null Returns the property as a string or null if it does not exist.
     */
    public static function getStringProperty($obj, $property, $allowEmptyValues = false)
    {
        return self::getProperty($obj, $property, self::TYPE_STRING, $allowEmptyValues);
    }

    /**
     * Gets a property from an object and converts it to an int.
     *
     * @param object|array $obj
     * @param string $property
     * @param bool $allowEmptyValues If true, values that are 0 or 0.0 are allowed.
     *
     * @return int|null Returns the property as an int or null if it does not exist.
     */
    public static function getIntProperty($obj, $property, $allowEmptyValues = false)
    {
        return self::getProperty($obj, $property, self::TYPE_INT, $allowEmptyValues);
    }

    /**
     * Gets a property from an object and converts it to a float.
     *
     * @param object|array $obj
     * @param string $property
     * @param bool $allowEmptyValues If true, values that are 0 or 0.0 are allowed.
     *
     * @return float|null Returns the property as a float or null if it does not exist.
     */
    public static function getFloatProperty($obj, $property, $allowEmptyValues = false)
    {
        return self::getProperty($obj, $property, self::TYPE_FLOAT, $allowEmptyValues);
    }

    /**
     * Gets a property from an object and converts it to a bool.
     *
     * @param object|array $obj
     * @param string $property
     * @param bool $allowEmptyValues If true, values that are 0 or 0.0 are allowed.
     *
     * @return bool|null Returns the property as a bool or null if it does not exist.
     */
    public static function getBoolProperty($obj, $property, $allowEmptyValues = false)
    {
        return self::getProperty($obj, $property, self::TYPE_BOOL, $allowEmptyValues);
    }

    /**
     * Gets a property from an object.
     *
     * @param object|array $obj
     * @param string $property
     * @param null|string $type Convert value to another type. Optional.
     * @param bool $allowEmptyValues If true, values that are 0 or 0.0 are allowed.
     *
     * @return mixed|null Returns the property value or null if it does not exist.
     */
    private static function getProperty($obj, $property, $type, $allowEmptyValues)
    {
        if (!is_array($obj) && !is_object($obj)) {
            return null;
        }

        if (is_object($obj) && !property_exists($obj, $property)) {
            return null;
        }
        if (is_array($obj) && !isset($obj[$property])) {
            return null;
        }

        if (is_object($obj)) {
            $value = self::getFromObj($obj, $property);
        } else {
            $value = self::getFromArray($obj, $property);
        }

        settype($value, $type);

        // Check for empty after the property has been casted to submitted the type.
        if (!$allowEmptyValues && self::isEmpty($value)) {
            return null;
        }

        return $value;
    }

    private static function getFromObj($obj, $property)
    {
        return $obj->{$property};
    }

    private static function getFromArray(array $arr, $key)
    {
        return $arr[$key];
    }

    /**
     * Checks if a variable is empty. Please take note that this function isn't like the PHP function, so a variable
     * will more likely not be empty when you expect it to be. Values like literal string '0' is therefore allowed and
     * is considered as "not empty".
     *
     * @param mixed $var
     *
     * @return bool
     */
    private static function isEmpty($var)
    {
        return ($var === '' || $var === 0 || $var === 0.0);
    }
}
