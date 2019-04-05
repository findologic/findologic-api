<?php

namespace FINDOLOGIC\Api\RequestBuilders;

use FINDOLOGIC\Api\Definitions\Endpoint;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\Client;
use FINDOLOGIC\Api\Config;
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
    protected $requiredParams = [
        QueryParameter::SHOP_URL,
    ];

    /** @var string */
    protected $endpoint;

    /** @var Client */
    protected $client;

    public function __construct()
    {
    }

    /**
     * Gets all currently set params.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
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
        $validator = new ParameterValidator([QueryParameter::SERVICE_ID => $value]);
        $validator->rule('shopkey', QueryParameter::SERVICE_ID);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::SERVICE_ID);
        }

        $this->addParam(QueryParameter::SERVICE_ID, $value);
        return $this;
    }

    /**
     * Sets the shopurl param. It is used to determine the service's url. Required.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#required_parameters
     * @return $this
     */
    public function setShopurl($value)
    {
        $shopUrlRegex = '/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&\'\(\)\*\+,;=.]+$/';
        if (!is_string($value) ||!preg_match($shopUrlRegex, $value)) {
            throw new InvalidParamException(QueryParameter::SHOP_URL);
        }

        $this->addParam(QueryParameter::SHOP_URL, $value);
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
     * Adds the group param. It is used to show only products for one or more specific groups.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#limiting_paging_parameters
     * @see https://docs.findologic.com/doku.php?id=smart_suggest_new#request
     * @param $value string
     * @return $this
     */
    public function addGroup($value)
    {
        $validator = new ParameterValidator([QueryParameter::GROUP => $value]);
        $validator->rule('string', QueryParameter::GROUP);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::GROUP);
        }

        $this->addParam(QueryParameter::GROUP, ['' => $value], self::ADD_VALUE);
        return $this;
    }

    /**
     * Adds an own param to the parameter list. This can be useful if you want to put some special key value pairs to
     * get a different response from FINDOLOGIC.
     *
     * Important Note: Both $key and $value are NOT validated. **Using this is neither recommended nor
     * supported.** If you think any parameter is missing for doing FINDOLOGIC requests, please create an issue before
     * using this method.
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
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
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
     * Takes care of checking the required params and whether they are set or not.
     * @throws ParamNotSetException If required params are not set.
     */
    public function checkRequiredParamsAreSet()
    {
        $validator = new Validator($this->params);
        $validator->rule('required', $this->requiredParams, true);

        if (!$validator->validate()) {
            throw new ParamNotSetException(key($validator->errors()));
        }
    }
}
