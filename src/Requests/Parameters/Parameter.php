<?php

namespace FINDOLOGIC\Api\Requests\Parameters;

abstract class Parameter
{
    /** @var string */
    private $name;

    /** @var mixed */
    private $value;

    /** @var array */
    private $validationRules = [];

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function __toString()
    {
        // TODO: To string.
        return json_encode(['']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function setValidationRules(array $rules)
    {
        $this->validationRules = $rules;
    }
}
