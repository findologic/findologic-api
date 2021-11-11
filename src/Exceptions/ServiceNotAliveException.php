<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Exceptions;

class ServiceNotAliveException extends FindologicApiException
{
    public function __construct(string $message)
    {
        parent::__construct(sprintf('The service is not alive. Reason: %s', $message));
    }
}
