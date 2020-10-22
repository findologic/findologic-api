<?php

namespace FINDOLOGIC\Api\Validators\Rules;

class Rule
{
    /** @var string */
    private $parameterName;

    /** @var string */
    private $rule;

    /** @var string|array|null */
    private $params;

    /**
     * @param string $parameterName
     * @param string $rule
     * @param string|array|null $params
     */
    public function __construct($parameterName, $rule, $params = null)
    {
        $this->parameterName = $parameterName;
        $this->rule = $rule;
        $this->params = $params;
    }

    public static function getInstance($parameterName, $rule, $params = null)
    {
        switch ($rule) {
            case 'regex':
                return new RegexRule($parameterName, $params);
            default:
                return new self($parameterName, $rule, $params);
        }
    }

    /**
     * @return string
     */
    public function getParameterName()
    {
        return $this->parameterName;
    }

    /**
     * @return string
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @return array|string|null
     */
    public function getParams()
    {
        return $this->params;
    }
}
