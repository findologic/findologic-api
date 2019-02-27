<?php

namespace FINDOLOGIC\Api\RequestBuilders;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\FindologicClient;
use FINDOLOGIC\Api\FindologicConfig;
use FINDOLOGIC\Api\Validators\ParameterValidator;
use InvalidArgumentException;
use Valitron\Validator;

abstract class RequestBuilder
{
    const
        SET_VALUE = 'set',
        ADD_VALUE = 'add';

    /** @var array */
    protected $params = [];

    /** @var array */
    protected $requiredParams = [];

    /** @var string */
    protected $endpoint;

    /** @var FindologicConfig */
    protected $config;

    /** @var FindologicClient */
    protected $findologicClient;

    /**
     * Sends a request to FINDOLOGIC. This may include an alivetest if the requested resource is a search or
     * a navigation request.
     */
    abstract public function sendRequest();

    public function __construct(FindologicConfig $config)
    {
        $this->config = $config;
        $this->findologicClient = new FindologicClient($config);
    }

    /**
     * Builds the request URL based on the set params.
     *
     * @return string
     */
    public function buildRequestUrl()
    {
        $shopUrl = $this->getParam(QueryParameter::SHOP_URL);
        $this->params['shopkey'] = $this->config->getShopkey();
        $queryParams = http_build_query($this->params);
        // Removes indexes from attrib[] param.
        $fullQueryString = preg_replace('/%5B\d+%5D/', '%5B%5D', $queryParams);

        $apiUrl = sprintf($this->config->getApiUrl(), $shopUrl, $this->endpoint);
        return sprintf('%s?%s', $apiUrl, $fullQueryString);
    }

    /**
     * Sets the shopkey param. It is used to determine the service. The shopkey param is set by default (from the
     * config). Only override this param if you are 100% sure you know what you're doing. Required.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#required_parameters
     * @return $this
     */
    public function setShopkey($value)
    {
        $validator = new ParameterValidator([QueryParameter::SHOPKEY => $value]);
        $validator->rule('shopkey', QueryParameter::SHOPKEY);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::SHOPKEY);
        }

        $this->addParam(QueryParameter::SHOPKEY, $value);
        return $this;
    }

    /**
     * Sets the query param. It is used as the search query.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#search_parameter
     * @return $this
     */
    public function setQuery($value)
    {
        $validator = new ParameterValidator([QueryParameter::QUERY => $value]);
        $validator->rule('string', QueryParameter::QUERY);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::QUERY);
        }

        $this->addParam(QueryParameter::QUERY, $value);
        return $this;
    }

    /**
     * Sets the count param. It is used to set the number of products that should be displayed.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#limiting_paging_parameters
     * @param $value int
     * @return $this
     */
    public function setCount($value)
    {
        $validator = new ParameterValidator([QueryParameter::COUNT => $value]);
        $validator->rule('equalOrHigherThanZero', QueryParameter::COUNT);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::COUNT);
        }

        $this->addParam(QueryParameter::COUNT, $value);
        return $this;
    }

    /**
     * Adds an own param to the parameter list. This can be useful if you want to put some special key value pairs to
     * get a different response from FINDOLOGIC.
     * IMPORTANT: Both $key or $value are NOT validated and NOT unit tested. **Using this is neither recommended nor
     * supported.**
     *
     * @param $key string
     * @param $value mixed
     * @param $method string Use ParameterBuilder::ADD_VALUE to add the param and not overwrite existing ones and
     * ParameterBuilder::SET_VALUE to overwrite existing params.
     *
     * @return $this
     */
    public function addIndividualParam($key, $value, $method)
    {
        $this->addParam($key, $value, $method);
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Sends an alivetest request. Returns nothing but will throw an exception if the alivetest is not successful.
     */
    protected function sendAlivetestRequest()
    {
        $this->findologicClient->request($this->buildAlivetestUrl(), true);
    }

    /**
     * Internal function that adds a certain param to all params array.
     *
     * @param $key string The key or the param name, that identifies the param.
     * @param $value mixed The value for the param.
     * @param string $method Can be either RequestBuilder::SET_VALUE or RequestBuilder::ADD_VALUE.
     * RequestBuilder::ADD_VALUE allows the value to be set multiple times and RequestBuilder::SET_VALUE will
     * override any existing ones.
     */
    protected function addParam($key, $value, $method = self::SET_VALUE)
    {
        switch ($method) {
            case self::SET_VALUE:
                $this->params[$key] = $value;
                break;
            case self::ADD_VALUE:
                if (isset($this->params[$key])) {
                    $this->params[$key] = array_merge_recursive($this->params[$key], $value);
                } else {
                    $this->params[$key] = $value;
                }
                break;
            default:
                throw new InvalidArgumentException('Unknown method type.');
        }
    }

    /**
     * Adds one required param.
     *
     * @param string $key
     */
    protected function addRequiredParam($key)
    {
        $this->requiredParams[] = $key;
    }

    /**
     * Adds the given keys to required params.
     *
     * @param array $keys
     */
    protected function addRequiredParams(array $keys)
    {
        $this->requiredParams = array_merge($this->requiredParams, $keys);
    }

    /**
     * Internal function that takes care of checking the required params and whether they are set or not. An exception
     * is thrown if they're not set.
     */
    protected function checkRequiredParamsAreSet()
    {
        $validator = new Validator($this->params);
        $validator->rule('required', $this->requiredParams, true);

        if (!$validator->validate()) {
            throw new ParamNotSetException(key($validator->errors()));
        }
    }

    /**
     * Builds the alivetest URL.
     *
     * @return string
     */
    protected function buildAlivetestUrl()
    {
        $shopUrl = $this->getParam(QueryParameter::SHOP_URL);
        $queryString = http_build_query([QueryParameter::SHOPKEY => $this->config->getShopkey()]);

        $apiUrl = sprintf($this->config->getApiUrl(), $shopUrl, Endpoint::ALIVETEST);
        return sprintf('%s?%s', $apiUrl, $queryString);
    }

    /**
     * Returns a specific param.
     *
     * @param string $key
     * @return mixed
     */
    private function getParam($key)
    {
        if (!isset($this->params[$key])) {
            throw new InvalidArgumentException('Unknown or unset param.');
        } else {
            return $this->params[$key];
        }
    }
}
