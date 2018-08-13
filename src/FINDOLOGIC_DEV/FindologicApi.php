<?php

namespace FINDOLOGIC_DEV;

use FINDOLOGIC_DEV\Definitions\RequestType;
use FINDOLOGIC_DEV\Exceptions\ConfigException;
use FINDOLOGIC_DEV\Exceptions\ParamException;
use FINDOLOGIC_DEV\Exceptions\ServiceNotAliveException;
use FINDOLOGIC_DEV\Helpers\ParameterBuilder;
use FINDOLOGIC_DEV\Objects\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FindologicApi extends ParameterBuilder
{
    /**
     * FindologicApi constructor.
     *
     * @param array $config containing the necessary config.
     *      $config = [
     *          FindologicApi::SHOPKEY              => (string) Service's shopkey. Required.
     *          FindologicApi::API_URL              => (string) Findologic API URL. Optional.
     *          FindologicApi::ALIVETEST_TIMEOUT    => (int|float) Timeout for an alivetest in seconds. Optional.
     *          FindologicApi::REQUEST_TIMEOUT      => (int|float) Timeout for a request in seconds. Optional.
     *          FindologicApi::HTTP_CLIENT          => (GuzzleHttp\Client) Client that is used for requests. Optional.
     *      ]
     * @throws ConfigException if the config is not valid.
     */
    public function __construct($config)
    {
        $this->validateConfig($config);

        // Set default httpClient if not explicitly set.
        $config[self::HTTP_CLIENT] = $config[self::HTTP_CLIENT] ?: new Client();

        // Config is validated and defaults are set.
        $this->config = array_merge($this->getConfig(), $config);
    }

    /**
     * Internal function to check whether the config is valid. Returns nothing and will throw an exception if the
     * config is not valid.
     *
     * @param array $config
     * @throws ConfigException
     */
    private function validateConfig($config)
    {
        // The config needs to be an array.
        if (!is_array($config)) {
            throw new ConfigException();
        }

        // All configuration values need to have a valid type.
        foreach ($config as $key => $value) {
            switch ($key) {
                case self::SHOPKEY:
                case self::API_URL:
                    if (!is_string($value)) {
                        throw new ConfigException();
                    }
                    break;
                case self::ALIVETEST_TIMEOUT:
                case self::REQUEST_TIMEOUT:
                    if (!is_int($value) || !is_float($value)) {
                        throw new ConfigException();
                    }
                    break;
                case self::HTTP_CLIENT:
                    if (!is_object($value)) {
                        throw new ConfigException();
                    }
            }
        }

        // Validate the shopkey against the shopkey format.
        if (!preg_match('/^[A-F0-9]{32,32}$/', $config[self::SHOPKEY])) {
            throw new ConfigException('Shopkey format is invalid.');
        }
    }

    /**
     * Returns the currently set config or only one setting when requesting a specific key.
     *
     * @param string|null $key
     * @return array
     */
    public function getConfig($key = null)
    {
        if ($key !== null) {
            return $this->config[$key];
        }
        return $this->config;
    }

    /**
     * Sends a search request to FINDOLOGIC and returns a Response object.
     *
     * @throws ServiceNotAliveException if the service is unable to respond.
     * @throws ParamException if the required params are not set.
     * @return Response
     */
    public function sendSearchRequest()
    {
        $this->checkRequiredParamsAreSet();

        $this->sendRequest(RequestType::ALIVETEST_REQUEST);
        return new Response($this->sendRequest(RequestType::SEARCH_REQUEST));
        //TODO: Send the search request with the set params.
        //TODO: Works with XML only. HTML will most likely not be supported.
    }

    /**
     * Sends a navigation request to FINDOLOGIC and returns a Response object.
     *
     * @throws ServiceNotAliveException if the service is unable to respond.
     * @throws ParamException if the required params are not set.
     * @return Response
     */
    public function sendNavigationRequest()
    {
        $this->checkRequiredParamsAreSet();

        $this->sendRequest(RequestType::ALIVETEST_REQUEST);
        return new Response($this->sendRequest(RequestType::NAVIGATION_REQUEST));
        //TODO: Send the navigation request with the set params.
        //TODO: Works with XML only. HTML will most likely not be supported.
    }

    /**
     * Sends a suggestion request to FINDOLOGIC and returns a Response object.
     *
     * @throws ServiceNotAliveException if the service is unable to respond.
     * @throws ParamException if the required params are not set.
     * @return Response
     */
    public function sendSuggestionRequest()
    {
        $this->checkRequiredParamsAreSet();

        //TODO: Check if a suggestion request requires an alivetest. If not this can be removed.
        $this->sendRequest(RequestType::ALIVETEST_REQUEST);
        return new Response($this->sendRequest(RequestType::SUGGESTION_REQUEST));
        //TODO: Send the suggestion request with the set params.
        //TODO: Works with JSON.
    }

    /**
     * Internal function that is used to send a request. It builds the URL and respects the timeout when sending a
     * request.
     *
     * @param string $requestType RequestType that is being used.
     *
     * @return string Response body.
     * @throws ServiceNotAliveException If the url is unreachable, returns an error message, unexpected body/code or
     * the timeout has been exceeded.
     */
    private function sendRequest($requestType)
    {
        /** @var Client $requestClient */
        $requestClient = $this->getConfig(self::HTTP_CLIENT);
        $timeout = $this->getConfig(self::REQUEST_TIMEOUT);

        if ($requestType == RequestType::ALIVETEST_REQUEST) {
            $timeout = $this->getConfig(self::ALIVETEST_TIMEOUT);
        }

        $requestUrl = $this->buildRequestUrl($requestType);

        try {
            $request = $requestClient->request(
                self::GET_METHOD,
                $requestUrl,
                ['connect_timeout' => $timeout]
            );
        } catch (GuzzleException $e) {
            throw new ServiceNotAliveException($e);
        }

        $responseBody = $request->getBody();
        $statusCode = $request->getStatusCode();

        // If it is an alivetest, the 'alive' body needs to be set. In any case the status code needs to be OK 200.
        if ((($requestType == RequestType::ALIVETEST_REQUEST && $responseBody == self::SERVICE_ALIVE_BODY) ||
                $requestType !== RequestType::ALIVETEST_REQUEST) && $statusCode === self::STATUS_OK) {
            return $responseBody;
        }

        throw new ServiceNotAliveException($responseBody);
    }

    /**
     * Internal function that takes care of checking the required params and whether they are set or not.
     *
     * @return bool Returns true on success, otherwise an ParamException will be thrown.
     */
    private function checkRequiredParamsAreSet()
    {
        $requiredParams = $this->getRequiredParams();

        // Check if all required params are set.
        foreach ($requiredParams as $paramName => $paramValue) {
            if (!array_key_exists($paramValue, $this->getParam())) {
                throw new ParamException($paramValue);
            }
        }

        return true;
    }
}
