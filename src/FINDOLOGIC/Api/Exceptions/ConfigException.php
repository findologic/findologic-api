<?php

namespace FINDOLOGIC\Api\Exceptions;

use RuntimeException;

class ConfigException extends RuntimeException
{
    public function __construct($message = 'Invalid FindologicApi config.')
    {
        parent::__construct($message);
    }
}
