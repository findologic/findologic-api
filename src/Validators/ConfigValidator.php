<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Validators;

use Valitron\Validator;

class ConfigValidator extends Validator
{
    /**
     * @param mixed $data
     * @param mixed $fields
     * @param string|null $lang
     * @param string|null $langDir
     */
    public function __construct($data = [], $fields = [], $lang = null, $langDir = null)
    {
        parent::__construct($data, $fields, $lang, $langDir);
        $this->addInstanceRule('shopkey', function ($field, $value) {
            return (is_string($value) && preg_match('/^[A-F0-9]{32}$/', $value));
        }, self::ERROR_DEFAULT);
    }
}
