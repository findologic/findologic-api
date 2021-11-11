<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Exceptions;

class InvalidParamException extends FindologicApiException
{
    public function __construct(string $param)
    {
        parent::__construct(sprintf('Parameter %s is not valid.', $param));
    }
}
