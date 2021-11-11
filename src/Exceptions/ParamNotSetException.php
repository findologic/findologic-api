<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Exceptions;

class ParamNotSetException extends FindologicApiException
{
    public function __construct(string $param)
    {
        parent::__construct(sprintf('Required param %s is not set.', $param));
    }
}
