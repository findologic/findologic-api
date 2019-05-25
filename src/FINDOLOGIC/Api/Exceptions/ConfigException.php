<?php

namespace FINDOLOGIC\Api\Exceptions;

use RuntimeException;

class ConfigException extends RuntimeException
{
    /**
     * @param string $parameter Parameter that is invalid.
     * @param string $message
     */
    public function __construct($parameter, $message = 'Config parameter "%s" is invalid')
    {
        parent::__construct(sprintf($message, $parameter));
    }
}
