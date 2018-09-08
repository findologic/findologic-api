<?php

namespace FINDOLOGIC\Helpers;

use FINDOLOGIC\Definitions\OrderType;
use FINDOLOGIC\Definitions\RequestType;
use FINDOLOGIC\Exceptions\InvalidParamException;
use InvalidArgumentException;

class ParameterBuilder
{
    const SHOPKEY = 'shopkey';

    const HTTP_CLIENT = 'httpClient';

    const API_URL = 'apiUrl';

    const ALIVETEST_TIMEOUT = 'alivetestTimeout';

    const REQUEST_TIMEOUT = 'requestTimeout';

    protected $config = [
        self::SHOPKEY,
        self::API_URL => self::DEFAULT_API_URL,
        self::ALIVETEST_TIMEOUT => self::DEFAULT_ALIVETEST_TIMEOUT,
        self::REQUEST_TIMEOUT => self::DEFAULT_REQUEST_TIMEOUT,
        self::HTTP_CLIENT
    ];

    const SHOP_URL = 'shopurl';

    const USER_IP = 'userip';

    const REFERER = 'referer';

    const REVISION = 'revision';

    const QUERY = 'query';

    const ATTRIB = 'attrib';

    const ORDER = 'order';

    const PROPERTIES = 'properties';

    const PUSH_ATTRIB = 'pushAttrib';

    const COUNT = 'count';

    const FIRST = 'first';

    const IDENTIFIER = 'identifier';

    const GROUP = 'group';

    const FORCE_ORIGINAL_QUERY = 'forceOriginalQuery';

    const INDIVIDUAL_PARAM = 'individualParam';

    // Defaults
    /** @var string URL Convention is https://API_URL/ps/SHOP_URL/ACTION.php */
    const DEFAULT_API_URL = 'https://service.findologic.com/ps/%s/%s';
    /** @var int|float */
    const DEFAULT_ALIVETEST_TIMEOUT = 1.0;
    /** @var int|float */
    const DEFAULT_REQUEST_TIMEOUT = 3.0;

    const SERVICE_ALIVE_BODY = 'alive';

    const SET_VALUE = 'set';
    const ADD_VALUE = 'add';

    const SLIDER_MIN = 'min';
    const SLIDER_MAX = 'max';

    const GET_METHOD = 'GET';
    const STATUS_OK = 200;

    protected $requiredParams = [
        //self::SHOPKEY, Is already required in the config and validated.
        self::SHOP_URL,
        self::USER_IP,
        self::REFERER,
        self::REVISION
    ];

    // TODO: Documentation does not require more params. But are userip etc. not relevant?
    protected $requiredParamsSuggest = [
        self::SHOPKEY,
        self::QUERY
    ];

    protected $params = [];

    /**
     * Sets the shopkey param. It is used to determine the service. Required.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#required_parameters
     * @return ParameterBuilder
     */
    public function setShopkey($value)
    {
        if (!is_string($value) || !preg_match('/^[A-F0-9]{32,32}$/', $value)) {
            throw new InvalidParamException(self::SHOPKEY);
        }

        $this->addParam(self::SHOPKEY, $value);
        return $this;
    }

    /**
     * Sets the shopurl param. It is used to determine the service's url. Required.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#required_parameters
     * @return ParameterBuilder
     */
    public function setShopurl($value)
    {
        $shopUrlRegex = '/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&\'\(\)\*\+,;=.]+$/';
        if (!is_string($value) ||!preg_match($shopUrlRegex, $value)) {
            throw new InvalidParamException(self::SHOP_URL);
        }

        $this->addParam(self::SHOP_URL, $value);
        return $this;
    }

    /**
     * Sets the userip param. It is used for billing and for the user identifier. Required.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#required_parameters
     * @return ParameterBuilder
     */
    public function setUserip($value)
    {
        $ipv4Regex= '/^(25[0-5]|2[0-4][0-9]|1?[0-9]{1,2})(.(25[0-5]|2[0-4][0-9]|1?[0-9]{1,2})){3}$/';
        $ipv6Regex = '/(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))/';

        // The parameter needs to be a string and an ipv4 or an ipv6 address.
        if (!is_string($value) || (!preg_match($ipv4Regex, $value) && !preg_match($ipv6Regex, $value))) {
            throw new InvalidParamException(self::USER_IP);
        }

        $this->addParam(self::USER_IP, $value);
        return $this;
    }

    /**
     * Sets the referer param. It is used to track the user's search history. Required.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#required_parameters
     * @return ParameterBuilder
     */
    public function setReferer($value)
    {
        if (!is_string($value) || !preg_match('/^((^https?:\/\/)|^www\.)/', $value)) {
            throw new InvalidParamException(self::REFERER);
        }

        $this->addParam(self::REFERER, $value);
        return $this;
    }

    /**
     * Sets the revision param. It is used to identify the version of the plugin. Can be set to 1.0.0 if you are not
     *      sure which value you should pass to the API. Required.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#required_parameters
     * @return ParameterBuilder
     */
    public function setRevision($value)
    {
        if (!is_string($value) || !preg_match('/^(\d+\.)?(\d+\.)?(\*|\d+)$/', $value)) {
            throw new InvalidParamException(self::REVISION);
        }

        $this->addParam(self::REVISION, $value);
        return $this;
    }

    /**
     * Sets the query param. It is used as the search query.
     *
     * @param $value string
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#search_parameter
     * @return ParameterBuilder
     */
    public function setQuery($value)
    {
        if (!is_string($value)) {
            throw new InvalidParamException(self::QUERY);
        }

        $this->addParam(self::QUERY, $value);
        return $this;
    }

    /**
     * Adds the attrib param. It is used to filter the search results.
     *
     * @param $filterName string
     * @param $value string
     * @param $specifier null|string is used for sliders such as price. Can be either 'min' or 'max'.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#search_parameter
     * @return ParameterBuilder
     */
    public function addAttribute($filterName, $value, $specifier = null)
    {
        $filterNameIsString = (is_string($filterName));
        $valueIsStringIntegerOrFloat = (is_string($value) || is_integer($value) || is_float($value));
        $specifierIsStringOrNull = (is_string($specifier) || $specifier === null);

        if ((!$filterNameIsString || !$valueIsStringIntegerOrFloat || !$specifierIsStringOrNull)) {
            throw new InvalidParamException(self::ATTRIB);
        }

        $this->addParam(self::ATTRIB, [$filterName => [$specifier => $value]], self::ADD_VALUE);
        return $this;
    }

    /**
     * Sets the order param. It is used to set the order of the products. Please use the given OrderType for setting
     * this value. E.g. OrderType::RELEVANCE for the FINDOLOGIC relevance.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#search_parameter
     * @param $value string
     * @return ParameterBuilder
     */
    public function setOrder($value)
    {
        if (!is_string($value) || !array_key_exists($value, OrderType::getList())) {
            throw new InvalidParamException(self::ORDER);
        }

        $this->addParam(self::ORDER, $value);
        return $this;
    }

    /**
     * Adds the property param. If set the response will display additional data that was exported in this column.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#search_parameter
     * @param $value string
     * @return ParameterBuilder
     */
    public function addProperty($value)
    {
        if (!is_string($value)) {
            throw new InvalidParamException(self::PROPERTIES);
        }

        $this->addParam(self::PROPERTIES, ['' => $value], self::ADD_VALUE);
        return $this;
    }

    /**
     * Adds the pushAttrib param. It is used to push products based on their attributes and on the factor. We recommend
     * using weights between 1 and 3 for the factor.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#search_parameter
     * @see https://docs.findologic.com/doku.php?id=personalization
     * @param $key string Name of the Filter. E.g. Color
     * @param $value string Value of the Filter. E.g. Black
     * @param $factor int|float Indicates how much the pushed filter influences the result.
     * @return ParameterBuilder
     */
    public function addPushAttrib($key, $value, $factor)
    {
        if (!(is_string($key) && is_string($value) && (is_int($factor) || is_float($factor)))) {
            throw new InvalidParamException(self::PUSH_ATTRIB);
        }

        $this->addParam(self::PUSH_ATTRIB, [$key => [$value => $factor]], self::ADD_VALUE);
        return $this;
    }

    /**
     * Sets the count param. It is used to set the number of products that should be displayed.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:request#limiting_paging_parameters
     * @param $value int
     * @return ParameterBuilder
     */
    public function setCount($value)
    {
        if (!is_integer($value)) {
            throw new InvalidParamException(self::COUNT);
        }

        $this->addParam(self::COUNT, $value);
        return $this;
    }

    /**
     * Sets the first param. It is used for pagination between pages. If your count is for example 20 and you switch
     * to the second page, set this parameter to 20 --> the first product on the next page. Do not set the parameter to
     * 21, because the product listing is 0-based.
     *
     * @param $value int
     * @return ParameterBuilder
     */
    public function setFirst($value)
    {
        if (!is_integer($value)) {
            throw new InvalidParamException(self::FIRST);
        }

        $this->addParam(self::FIRST, $value);
        return $this;
    }

    /**
     * Sets the identifier param. It is used to display only the item that is given. The query param is ignored.
     *
     * @param $value string ID of the item.
     * @return ParameterBuilder
     */
    public function setIdentifier($value)
    {
        if (!is_string($value)) {
            throw new InvalidParamException(self::IDENTIFIER);
        }

        $this->addParam(self::IDENTIFIER, $value);
        return $this;
    }

    /**
     * Adds the group param. It is used to show only products for one or more specific groups.
     *
     * @param $value string
     * @return ParameterBuilder
     */
    public function addGroup($value)
    {
        if (!is_string($value)) {
            throw new InvalidParamException(self::GROUP);
        }

        $this->addParam(self::GROUP, ['' => $value], self::ADD_VALUE);
        return $this;
    }

    /**
     * Adds the forceOriginalQuery param. It is used for Smart Did You Mean.
     *
     * @return ParameterBuilder
     */
    public function setForceOriginalQuery()
    {
        $this->addParam(self::FORCE_ORIGINAL_QUERY, 1);
        return $this;
    }

    /**
     * Adds an own param to the parameter list. This can be useful if you want to put some special key value pairs to
     * get a different response from FINDOLOGIC.
     * IMPORTANT: Both $key or $value are NOT validated and NOT tested. **Using this is neither recommended nor
     * supported.**
     *
     * @param $key mixed
     * @param $value mixed
     * @param $method string Use ParameterBuilder::ADD_VALUE to add the param and not overwrite existing ones and
     * ParameterBuilder::SET_VALUE to overwrite existing params.
     *
     * @return ParameterBuilder
     */
    public function addIndividualParam($key, $value, $method)
    {
        $this->addParam($key, $value, $method);
        return $this;
    }

    /**
     * Returns a specific param or all if no explicit param is given.
     *
     * @param string|null $param
     * @return string|array
     */
    public function getParam($param = null)
    {
        if ($param !== null) {
            return $this->params[$param];
        }
        return $this->params;
    }

    /**
     * Internal function that adds a certain param to all params array.
     *
     * @param $key string The key or the param name, that identifies the param.
     * @param $value string The value for the param.
     * @param string $method Can be either 'set' or 'add'. 'add' allows the value to be set multiple times and 'set'
     *      will override any existing ones.
     */
    private function addParam($key, $value, $method = self::SET_VALUE)
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
     * Returns all params that are required to send a request.
     *
     * @return array
     */
    protected function getRequiredParams()
    {
        return $this->requiredParams;
    }

    /**
     * Builds the request URL based on the request type and the set params.
     *
     * @param $requestType string
     * @return string
     */
    protected function buildRequestUrl($requestType)
    {
        $shopUrl = $this->params[self::SHOP_URL];

        if ($requestType !== RequestType::ALIVETEST_REQUEST) {
            $this->params[self::SHOPKEY] = $this->config[self::SHOPKEY];
            $queryParams = http_build_query($this->params);
            // Removes indexes from attrib[] param.
            $fullQueryString = preg_replace('/%5B\d+%5D/', '%5B%5D', $queryParams);
        } else {
            // The alivetest only requires the shopkey param.
            $fullQueryString = http_build_query([self::SHOPKEY => $this->config[self::SHOPKEY]]);
        }

        $apiUrl = sprintf($this->config[self::API_URL], $shopUrl, $requestType);
        return sprintf('%s?%s', $apiUrl, $fullQueryString);
    }
}
