<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Exceptions;

use RuntimeException;

class InvalidParamException extends RuntimeException
{
    public function __construct(string $param)
    {
        parent::__construct(sprintf('Parameter %s is not valid.', $param));
    }
}
