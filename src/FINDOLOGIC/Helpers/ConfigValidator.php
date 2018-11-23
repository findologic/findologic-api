<?php

namespace FINDOLOGIC\Helpers;

use Valitron\Validator;

class ConfigValidator extends Validator
{
    public function __construct(array $data = [], array $fields = [], $lang = null, $langDir = null)
    {
        parent::__construct($data, $fields, $lang, $langDir);
        $this->addInstanceRule('shopkey', function ($field, $value) {
            return (is_string($value) && preg_match('/^[A-F0-9]{32}$/', $value));
        }, self::ERROR_DEFAULT);
        $this->addInstanceRule('object', function ($field, $value) {
            return (is_object($value));
        }, self::ERROR_DEFAULT);
    }
}
