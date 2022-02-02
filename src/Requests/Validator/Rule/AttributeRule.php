<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\Validator\Rule;

use Rakit\Validation\Rule;

class AttributeRule extends Rule
{
    public function check($value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $attributeKey => $values) {
            if (!is_string($attributeKey)) {
                return false;
            }

            if (!is_array($values)) {
                return false;
            }

            foreach ($values as $index => $attributeValue) {
                if (!is_numeric($index)) {
                    return false;
                }

                if (!is_string($attributeValue) && !is_numeric($attributeValue)) {
                    return false;
                }
            }
        }

        return true;
    }
}
