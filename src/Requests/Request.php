<?php

namespace FINDOLOGIC\Api\Requests;

use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Definitions\OutputAdapter;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\Requests\Autocomplete\SuggestRequest;
use FINDOLOGIC\Api\Requests\Item\ItemUpdateRequest;
use FINDOLOGIC\Api\Requests\SearchNavigation\NavigationRequest;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchRequest;
use FINDOLOGIC\Api\Validators\ParameterValidator;
use InvalidArgumentException;
use Valitron\Validator;

abstract class Request
{
    const TYPE_SEARCH = 0;
    const TYPE_NAVIGATION = 1;
    const TYPE_SUGGEST_V3 = 2;
    const TYPE_ALIVETEST = 3;
    const TYPE_ITEM_UPDATE = 4;

    const SET_VALUE = 'set';
    const ADD_VALUE = 'add';

    /** @var array */
    protected $params;

    /** @var string[] */
    protected $requiredParams = [
        QueryParameter::SHOP_URL,
    ];

    /** @var string */
    protected $endpoint;

    /** @var string */
    protected $method;

    /** @var string */
    protected $outputAdapter = OutputAdapter::XML_21;

    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    /**
     * @param int $type
     * @return Request
     */
    public static function getInstance($type)
    {
        switch ($type) {
            case self::TYPE_SEARCH:
                return new SearchRequest();
            case self::TYPE_NAVIGATION:
                return new NavigationRequest();
            case self::TYPE_SUGGEST_V3:
                return new SuggestRequest();
            case self::TYPE_ALIVETEST:
                return new AlivetestRequest();
            case self::TYPE_ITEM_UPDATE:
                return new ItemUpdateRequest();
            default:
                throw new InvalidArgumentException(sprintf('Unknown request type "%d"', $type));
        }
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
     * Returns the request body. An exception must be thrown on requests that do not support a request body.
     *
     * @return string|null
     */
    abstract public function getBody();

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
    public function setShopUrl($value)
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

        $this->addParam(QueryParameter::GROUP, [$value], self::ADD_VALUE);
        return $this;
    }

    /**
     * Adds the hashed usergroup param.
     *
     * @param $value string
     * @return $this
     */
    public function addUserGroup($value)
    {
        $validator = new ParameterValidator([QueryParameter::USERGROUP => $value]);
        $validator->rule('string', QueryParameter::USERGROUP);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::USERGROUP);
        }

        $this->addParam(QueryParameter::USERGROUP, [$value], self::ADD_VALUE);
        return $this;
    }

    /**
     * Adds the outputAdapter param. It is used to override the output format.
     *
     * @param string $value One of available OutputAdapter. E.g. OutputAdapter::XML_21.
     * @return $this
     */
    public function setOutputAdapter($value)
    {
        $validator = new ParameterValidator([QueryParameter::OUTPUT_ADAPTER => $value]);
        $validator
            ->rule('isOutputAdapterParam', QueryParameter::OUTPUT_ADAPTER);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::OUTPUT_ADAPTER);
        }

        $this->addParam(QueryParameter::OUTPUT_ADAPTER, $value);
        $this->outputAdapter = $value;

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
     * @param $method string Use Request::ADD_VALUE to add the param and not overwrite existing ones and
     * Request::SET_VALUE to overwrite existing params.
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
     * @return string|null
     */
    public function getOutputAdapter()
    {
        return $this->outputAdapter;
    }

    /**
     * Internal function that adds a certain param to all params array.
     *
     * @param $key string The key or the param name, that identifies the param.
     * @param $value mixed The value for the param.
     * @param string $method Can be either Request::SET_VALUE or Request::ADD_VALUE.
     * Request::ADD_VALUE allows the value to be set multiple times and Request::SET_VALUE will
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
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
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

    /**
     * Builds the request URL based on the set params.
     *
     * @param Config $config
     * @return string
     */
    public function buildRequestUrl(Config $config)
    {
        $params = $this->getParams();

        $shopUrl = $params[QueryParameter::SHOP_URL];
        // If the shopkey was not manually overridden, we take the shopkey from the config.
        if (!isset($params[QueryParameter::SERVICE_ID])) {
            $params['shopkey'] = $config->getServiceId();
        }

        if (isset($params[QueryParameter::ATTRIB])) {
            foreach ($params[QueryParameter::ATTRIB] as $key => $values) {
                if (is_string(array_values($values)[0])) {
                    continue; // Nothing to do for single select filters.
                }

                // Multiselect filters are merged with array_merge_recursive, which causes them to be in an array
                // without a key associated to them. This makes the values appear to be one level too low. We fix
                // this by manually moving them to the correct level.
                $params[QueryParameter::ATTRIB][$key] = array_values($values)[0];
            }
        }

        $queryParams = http_build_query($params);

        $apiUrl = sprintf($config->getApiUrl(), $shopUrl, $this->getEndpoint());
        return sprintf('%s?%s', $apiUrl, $queryParams);
    }
}
