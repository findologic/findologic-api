<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Validator;

use Psr\Http\Message\RequestInterface;
use Rakit\Validation\Validation;

abstract class RequestValidator
{
    abstract public function makeValidation(RequestInterface $request): Validation;

    protected function parseQueryParams(RequestInterface $request): array
    {
        parse_str($request->getUri()->getQuery(), $queryParams);

        return $queryParams ?? [];
    }
}
