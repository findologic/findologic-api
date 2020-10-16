<?php

namespace FINDOLOGIC\Api\Requests\Parameters;

use InvalidArgumentException;

class ParameterBag
{
    /** @var Parameter[] */
    private $params = [];

    /**
     * Adds the given parameter to the parameter bag.
     *
     * @param Parameter $parameter
     */
    public function add(Parameter $parameter)
    {
        $this->params[$parameter->getName()] = $parameter;
    }

    /**
     * Searches for the given parameter and returns it. If it can not be found, an exception may be thrown.
     *
     * @param string $name
     * @return Parameter
     */
    public function get($name)
    {
        if (!isset($this->params[$name])) {
            throw new InvalidArgumentException(sprintf('Param with name "%s" does not exist.', $name));
        }

        return $this->params[$name];
    }

    /**
     * Checks if the given param is set.
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->params[$name]);
    }

    /**
     * Returns all params.
     *
     * @return Parameter[]
     */
    public function all()
    {
        return $this->params;
    }

    /**
     * Removes the given parameter name from all parameters. If the parameter does not exist, an exception may
     * be thrown.
     *
     * @param string $name
     */
    public function delete($name)
    {
        if (!isset($this->params[$name])) {
            throw new InvalidArgumentException(sprintf('Param with name "%s" does not exist.', $name));
        }

        unset($this->params[$name]);
    }
}
