<?php

namespace FINDOLOGIC\Request\ParameterValidator;

use InvalidArgumentException;

class ParameterValidator
{
    /**
     * Validates the given shopkey.
     *
     * @param $value string Shopkey.
     * @throws InvalidArgumentException If the shopkey format is invalid.
     */
    public static function validateShopkey($value) {
        if (!preg_match('^[A-F0-9]{32,32}$', $value)){
            throw new InvalidArgumentException('Shopkey format is invalid.');
        }
    }
}