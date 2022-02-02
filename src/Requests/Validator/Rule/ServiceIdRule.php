<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Validator\Rule;

use Rakit\Validation\Rule;

class ServiceIdRule extends Rule
{
    public function check($value): bool
    {
        return (is_string($value) && preg_match('/^[A-F0-9]{32}$/', $value));
    }
}
