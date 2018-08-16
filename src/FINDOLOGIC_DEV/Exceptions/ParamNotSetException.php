<?php

namespace FINDOLOGIC_DEV\Exceptions;

use RuntimeException;

class ParamNotSetException extends RuntimeException
{
    public function __construct($param)
    {
        parent::__construct(sprintf('Required param %s is not set.', $param));
    }
}
