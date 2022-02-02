<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Validator\Rule;

class StringRule extends \Rakit\Validation\Rule
{
    public function check($value): bool
    {
        return is_string($value);
    }
}
