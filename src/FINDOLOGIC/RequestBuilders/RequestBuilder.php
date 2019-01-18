<?php

namespace FINDOLOGIC\RequestBuilders;

use FINDOLOGIC\FindologicConfig;
use InvalidArgumentException;

abstract class RequestBuilder
{
    const
        SET_VALUE = 'set',
        ADD_VALUE = 'add';

    protected $params = [];
    protected $requiredParams = [];
    /** @var string */
    protected $endpoint;
    /** @var FindologicConfig */
    protected $config;

    public function __construct(FindologicConfig $config)
    {
        $this->config = $config;
    }

    abstract public function sendRequest();

    /**
     * Internal function that adds a certain param to all params array.
     *
     * @param $key string The key or the param name, that identifies the param.
     * @param $value mixed The value for the param.
     * @param string $method Can be either ParameterBuilder::SET_VALUE or ParameterBuilder::ADD_VALUE.
     * ParameterBuilder::ADD_VALUE allows the value to be set multiple times and ParameterBuilder::SET_VALUE will
     * override any existing ones.
     */
    protected function addParam($key, $value, $method = self::SET_VALUE)
    {
        if ($method == self::SET_VALUE) {
            $this->params[$key] = $value;
        } elseif ($method == self::ADD_VALUE) {
            if (isset($this->params[$key])) {
                $this->params[$key] = array_merge_recursive($this->params[$key], $value);
            } else {
                $this->params[$key] = $value;
            }
        } else {
            throw new InvalidArgumentException('Unknown method type.');
        }
    }

    /**
     * Builds the request URL based on the set params.
     *
     * @return string
     */
    protected function buildRequestUrl()
    {
        $shopUrl = $this->params['shopurl'];
        $this->params['shopkey'] = $this->config->getShopkey();
        $queryParams = http_build_query($this->params);
        // Removes indexes from attrib[] param.
        $fullQueryString = preg_replace('/%5B\d+%5D/', '%5B%5D', $queryParams);

        $apiUrl = sprintf($this->config->getApiUrl(), $shopUrl, $this->endpoint);
        return sprintf('%s?%s', $apiUrl, $fullQueryString);
    }
}
