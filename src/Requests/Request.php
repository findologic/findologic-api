<?php

declare(strict_types=1);

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
    public const TYPE_SEARCH = 0;
    public const TYPE_NAVIGATION = 1;
    public const TYPE_SUGGEST_V3 = 2;
    public const TYPE_ALIVETEST = 3;
    public const TYPE_ITEM_UPDATE = 4;

    public const SET_VALUE = 'set';
    public const ADD_VALUE = 'add';

    /** @var array<string, array<string|int|float>|string|int|float> $params  */
    protected array $params;

    /** @var string[] */
    protected array $requiredParams = [
        QueryParameter::SHOP_URL,
    ];

    protected string $endpoint;
    protected string $method;
    protected ?string $outputAdapter = OutputAdapter::XML_21;

    /**
     * @param array<string, array<string|int|float>|string|int|float> $params
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public static function getInstance(int $type): Request
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
     * @return array<string, array<string|int|float>|string|int|float>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Returns the request body. An exception must be thrown on requests that do not support a request body.
     */
    abstract public function getBody(): ?string;

    /**
     * Sets the shopkey param. It is used to determine the service. The shopkey param is set by default (from the
     * config). Only override this param if you are 100% sure you know what you're doing. Required.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#required_parameters
     */
    public function setShopkey(string $value): self
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
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#required_parameters
     * @return static
     */
    public function setShopUrl(string $value): self
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
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#search_parameters
     */
    public function setQuery(string $value): self
    {
        $this->addParam(QueryParameter::QUERY, $value);

        return $this;
    }

    /**
     * Sets the count param. It is used to set the number of products that should be displayed.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#limiting_paging_parameters
     */
    public function setCount(int $value): self
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
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#limiting_paging_parameters
     * @see https://service.findologic.com/ps/centralized-frontend/spec/
     */
    public function addGroup(string $value): self
    {
        $this->addParam(QueryParameter::GROUP, [$value], self::ADD_VALUE);

        return $this;
    }

    /**
     * Adds the hashed usergroup param.
     *
     * @see https://service.findologic.com/ps/centralized-frontend/spec/
     */
    public function addUserGroup(string $value): self
    {
        $this->addParam(QueryParameter::USERGROUP, [$value], self::ADD_VALUE);

        return $this;
    }

    /**
     * Adds the outputAdapter param. It is used to override the output format.
     *
     * @param string $value One of available OutputAdapter. E.g. OutputAdapter::XML_21.
     */
    public function setOutputAdapter(string $value): self
    {
        $validator = new ParameterValidator([QueryParameter::OUTPUT_ADAPTER => $value]);
        $validator->rule('isOutputAdapterParam', QueryParameter::OUTPUT_ADAPTER);

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
     * @param mixed $value
     * @param string $method Use Request::ADD_VALUE to add the param and not overwrite existing ones and
     * Request::SET_VALUE to overwrite existing params.
     *
     * @return $this
     */
    public function addIndividualParam(string $key, $value, string $method)
    {
        $this->addParam($key, $value, $method);

        return $this;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getOutputAdapter(): ?string
    {
        return $this->outputAdapter;
    }

    /**
     * Internal function that adds a certain param to all params array.
     *
     * @param string $key The key or the param name, that identifies the param.
     * @param mixed $value The value for the param.
     * @param string $method Can be either Request::SET_VALUE or Request::ADD_VALUE.
     * Request::ADD_VALUE allows the value to be set multiple times and Request::SET_VALUE will
     * override any existing ones.
     */
    protected function addParam(string $key, $value, string $method = self::SET_VALUE): void
    {
        switch ($method) {
            case self::SET_VALUE:
                $this->params[$key] = $value;
                break;
            case self::ADD_VALUE:
                if (isset($this->params[$key]) && is_array($this->params[$key])) {
                    $this->params[$key] = array_merge_recursive($this->params[$key], $value);
                } else {
                    $this->params[$key] = $value;
                }
                break;
            default:
                throw new InvalidArgumentException('Unknown method type.');
        }
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Adds one required param.
     */
    protected function addRequiredParam(string $key): void
    {
        $this->requiredParams[] = $key;
    }

    /**
     * Adds the given keys to required params.
     *
     * @param string[] $keys
     */
    protected function addRequiredParams(array $keys): void
    {
        $this->requiredParams = array_merge($this->requiredParams, $keys);
    }

    /**
     * Takes care of checking the required params and whether they are set or not.
     * @throws ParamNotSetException If required params are not set.
     */
    public function checkRequiredParamsAreSet(): void
    {
        $validator = new Validator($this->params);
        $validator->rule('required', $this->requiredParams, true);

        if (!$validator->validate()) {
            throw new ParamNotSetException((string)key((array)$validator->errors()));
        }
    }

    /**
     * Builds the request URL based on the set params.
     */
    public function buildRequestUrl(Config $config): string
    {
        $params = $this->getParams();

        if (!isset($params[QueryParameter::SHOP_URL]) || !is_string($params[QueryParameter::SHOP_URL])) {
            throw new InvalidParamException('shopurl');
        }
        $shopUrl = $params[QueryParameter::SHOP_URL];

        // If the shopkey was not manually overridden, we take the shopkey from the config.
        if (!isset($params[QueryParameter::SERVICE_ID])) {
            $params['shopkey'] = $config->getServiceId();
        }

        if (isset($params[QueryParameter::ATTRIB]) && is_array($params[QueryParameter::ATTRIB])) {
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
