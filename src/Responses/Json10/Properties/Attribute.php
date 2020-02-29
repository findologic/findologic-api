<?php

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Attribute
{
    /** @var string */
    private $name;

    /** @var string[] */
    private $values;

    public function __construct(array $attribute)
    {
        $this->name = ResponseHelper::getStringProperty($attribute, 'name');
        if (isset($attribute['values'])) {
            $this->values = $attribute['values'];
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getValues()
    {
        return $this->values;
    }
}
