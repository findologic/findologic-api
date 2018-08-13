<?php

namespace FINDOLOGIC_DEV\Helpers;

use FINDOLOGIC_DEV\Definitions\RequestType;
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

    // Defaults
    /** @var string URL Convention is https://API_URL/SHOP_URL/ACTION.php */
    const DEFAULT_API_URL = 'https://service.findologic.com/%s/%s';
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
        $this->addParam(self::COUNT, $value);
        return $this;
    }

    /**
     * Sets the first param. It is used for pagination between pages. If your count is for example 20 and you switch
     * to the second page, set this parameter to 20 --> the first product on the next page. Do not set the parameter to
     * 21, because the product listing is 0-based.
     *
     * @param $value
     * @return ParameterBuilder
     */
    public function setFirst($value)
    {
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
        $this->addParam(self::GROUP, ['' => $value], self::ADD_VALUE);
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
            try {
                $this->params[$key] = array_merge_recursive($this->params[$key], $value);
            } catch (\Exception $e) {
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
     * Builds the request URL based on the request type and the set params and returns it.
     *
     * @param $requestType string
     * @return string
     */
    protected function buildRequestUrl($requestType)
    {
        $shopUrl = $this->params[self::SHOP_URL];

        if ($requestType !== RequestType::ALIVETEST_REQUEST) {
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
