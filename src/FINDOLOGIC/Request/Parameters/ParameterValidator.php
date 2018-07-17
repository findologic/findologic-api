<?php

namespace FINDOLOGIC\Request\ParameterValidator;

use InvalidArgumentException;

class ParameterValidator
{
    const REQUIRED_PARAMS = ['shopkey', 'shopurl', 'userip', 'referer', 'revision'];

    /**
     * Validates the given shopkey.
     *
     * @param $value string Shopkey.
     * @throws InvalidArgumentException If the shopkey format is invalid.
     */
    public static function validateShopkey($value)
    {
        if (!preg_match('/^[A-F0-9]{32,32}$/', $value)) {
            throw new InvalidArgumentException('Shopkey format is invalid.');
        }
    }

    /**
     * Validates the given shopurl.
     *
     * @param $value string Shopurl.
     * @throws InvalidArgumentException If the shopurl format is invalid.
     */
    public static function validateShopurl($value)
    {
        if (!preg_match('/^((^https?:\/\/)|^www\.)/', $value)) {
            throw new InvalidArgumentException('Shopurl format is invalid.');
        }
    }

    /**
     * Validates the given userip.
     *
     * @param $value string Userip.
     * @throws InvalidArgumentException If the userip format is invalid.
     */
    public static function validateUserip($value)
    {
        $useripRegex = '/^(25[0-5]|2[0-4][0-9]|1?[0-9]{1,2})(.(25[0-5]|2[0-4][0-9]|1?[0-9]{1,2})){3}$/';

        if (!preg_match($useripRegex, $value)) {
            throw new InvalidArgumentException('Userip format is invalid.');
        }
    }

    /**
     * Validates the given referer.
     *
     * @param $value string Referer.
     * @throws InvalidArgumentException If the referer format is invalid.
     */
    public static function validateReferer($value)
    {
        if (!preg_match('/^((^https?:\/\/)|^www\.)/', $value)) {
            throw new InvalidArgumentException('Referer format is invalid.');
        }
    }

    /**
     * Validates the given revision.
     *
     * @param $value string Revision.
     * @throws InvalidArgumentException If the revision format is invalid.
     */
    public static function validateRevision($value)
    {
        if (!preg_match('^(\d+\.)?(\d+\.)?(\*|\d+)$', $value)) {
            throw new InvalidArgumentException('Revision format is invalid.');
        }
    }

    /**
     * Validates the given params and checks whether all required params are set.
     *
     * @param $params array
     * @throws InvalidArgumentException If not all required params are set.
     */
    public static function requiredParamsAreSet($params)
    {
        foreach (self::REQUIRED_PARAMS as $requiredParam) {
            if (!array_key_exists($requiredParam, $params)) {
                $errorMessage = sprintf('The parameter "%s" is required but not set.', $requiredParam);
                throw new InvalidArgumentException($errorMessage);
            }
        }
    }
}
