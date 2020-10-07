<?php

namespace FINDOLOGIC\Api\Validators;

use FINDOLOGIC\Api\Definitions\BlockType;
use FINDOLOGIC\Api\Definitions\OrderType;
use FINDOLOGIC\Api\Definitions\OutputAdapter;
use Valitron\Validator;

class ParameterValidator extends Validator
{
    /**
     * Semantic versioning regex.
     * @see https://semver.org/
     * @see https://regex101.com/r/Ly7O1x/3/
     */
    const SEMVER_VERSION_REGEX = '/^(?P<major>0|[1-9]\d*)\.(?P<minor>0|[1-9]\d*)\.(?P<patch>0|[1-9]\d*)(?:-(?P<prerelease>(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+(?P<buildmetadata>[0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/';

    public function __construct(array $data = [], array $fields = [], $lang = null, $langDir = null)
    {
        parent::__construct($data, $fields, $lang, $langDir);
        $this->addInstanceRule('shopkey', function ($field, $value) {
            return (is_string($value) && preg_match('/^[A-F0-9]{32}$/', $value));
        }, self::ERROR_DEFAULT);
        $this->addInstanceRule('version', function ($field, $value) {
            return (is_string($value) && preg_match(self::SEMVER_VERSION_REGEX, $value));
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
        $this->addInstanceRule('isOutputAdapterParam', function ($field, $value) {
            return (in_array($value, OutputAdapter::getConstants()));
        }, self::ERROR_DEFAULT);
        $this->addInstanceRule('equalOrHigherThanZero', function ($field, $value) {
            return (is_integer($value) && $value >= 0);
        }, self::ERROR_DEFAULT);
    }
}
