<?php

namespace FINDOLOGIC\Api\Validators;

use FINDOLOGIC\Api\Definitions\BlockType;
use FINDOLOGIC\Api\Definitions\OrderType;
use Valitron\Validator;

class ParameterValidator extends Validator
{
    public function __construct(array $data = [], array $fields = [], $lang = null, $langDir = null)
    {
        parent::__construct($data, $fields, $lang, $langDir);
        $this->addInstanceRule('shopkey', function ($field, $value) {
            return (is_string($value) && preg_match('/^[A-F0-9]{32}$/', $value));
        }, self::ERROR_DEFAULT);
        $this->addInstanceRule('version', function ($field, $value) {
            return (is_string($value) && preg_match('/^(\d+\.)?(\d+\.)?(\*|\d+)$/', $value));
        }, self::ERROR_DEFAULT);
        $this->addInstanceRule('string', function ($field, $value) {
            return (is_string($value));
        }, self::ERROR_DEFAULT);
        $this->addInstanceRule('stringOrNull', function ($field, $value) {
            return (is_string($value) || is_null($value));
        }, self::ERROR_DEFAULT);
        $this->addInstanceRule('stringOrNumeric', function ($field, $value) {
            return (is_string($value) || is_numeric($value));
        }, self::ERROR_DEFAULT);
        $this->addInstanceRule('isOrderParam', function ($field, $value) {
            return (in_array($value, OrderType::getConstants()));
        }, self::ERROR_DEFAULT);
        $this->addInstanceRule('isAutocompleteBlockParam', function ($field, $value) {
            return (in_array($value, BlockType::getConstants()));
        }, self::ERROR_DEFAULT);
        $this->addInstanceRule('equalOrHigherThanZero', function ($field, $value) {
            return (is_integer($value) && $value >= 0);
        }, self::ERROR_DEFAULT);
    }
}
