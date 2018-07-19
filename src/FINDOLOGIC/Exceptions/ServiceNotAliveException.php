<?php

namespace FINDOLOGIC\Exceptions;

class ServiceNotAliveException extends \RuntimeException
{
    public function __construct($alivetestUrl)
    {
        parent::__construct(sprintf('The service is not alive! Alivetest URL was %s.', $alivetestUrl));
    }
}