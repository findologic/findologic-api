<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Exceptions;

class ConfigException extends FindologicApiException
{
    public function __construct(string $parameter, string $message = 'Config parameter "%s" is invalid')
    {
        parent::__construct(sprintf($message, $parameter));
    }
}
