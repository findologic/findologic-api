<?php

namespace FINDOLOGIC\Api\Exceptions;

use RuntimeException;

class ConfigException extends RuntimeException
{
    public function __construct($message = 'Invalid config supplied.')
    {
        parent::__construct($message);
    }
}
