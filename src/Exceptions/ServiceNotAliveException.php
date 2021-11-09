<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Exceptions;

use RuntimeException;

class ServiceNotAliveException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct(sprintf('The service is not alive. Reason: %s', $message));
    }
}
