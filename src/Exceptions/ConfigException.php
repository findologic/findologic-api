<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Exceptions;

use RuntimeException;

class ConfigException extends RuntimeException
{
    public function __construct(string $parameter, string $message = 'Config parameter "%s" is invalid')
    {
        parent::__construct(sprintf($message, $parameter));
    }
}
