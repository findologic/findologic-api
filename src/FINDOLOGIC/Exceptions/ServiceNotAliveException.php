<?php

namespace FINDOLOGIC\Exceptions;

use RuntimeException;

class ServiceNotAliveException extends RuntimeException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
