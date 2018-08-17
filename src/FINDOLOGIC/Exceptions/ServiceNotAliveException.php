<?php

namespace FINDOLOGIC\Exceptions;

use RuntimeException;

class ServiceNotAliveException extends RuntimeException
{
    public function __construct($message)
    {
        parent::__construct(sprintf('The service is not alive. Reason: %s', $message));
    }
}
