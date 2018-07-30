<?php

namespace FINDOLOGIC_DEV\Exceptions;

use RuntimeException;

class ServiceNotAliveException extends RuntimeException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
