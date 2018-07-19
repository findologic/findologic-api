<?php

namespace FINDOLOGIC\Exceptions;

class ServiceNotAliveException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('The service is not alive!');
    }
}