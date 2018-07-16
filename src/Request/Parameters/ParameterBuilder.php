<?php

namespace Request\ParameterBuilder;

use Request\Parameters\Types\Shopkey;

class ParameterBuilder
{
    private $params = [];

    /**
     * @param $value string Required shopkey. It is used to determine the service.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#required_parameters
     */
    public function addShopkey($value)
    {
        $shopkey = new Shopkey();
        $shopkey->setValue($value);
        $this->addParam(Shopkey::PARAM_KEY, $value);
    }

    /**
     * @return array Returns all params as an array.
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param $key string The key or the param name, that identifies the param.
     * @param $value string The value for the param.
     */
    private function addParam($key, $value)
    {
        $this->params[] = [$key => $value];
    }
}