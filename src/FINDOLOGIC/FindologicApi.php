<?php

namespace FINDOLOGIC;

use FINDOLOGIC\Definitions\RequestType;
use FINDOLOGIC\Exceptions\ConfigException;
use FINDOLOGIC\Exceptions\ParamNotSetException;
use FINDOLOGIC\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Helpers\ParameterBuilder;
use FINDOLOGIC\Objects\JsonResponse;
use FINDOLOGIC\Objects\XmlResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FindologicApi extends ParameterBuilder
{
    /**
     * Can be used to get the response time from the FINDOLOGIC API in microseconds.
     *
     * @var float $responseTime
     */
    private $responseTime;

    /**
     * Saves the unix timestamp in microseconds of the last made request.
     *
     * @var float $requestUnixTimestamp
     */
    private $requestUnixTimestamp;

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
    private function validateConfig(array $config)
    {
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
                    if (!is_int($value) && !is_float($value)) {
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
     * @return mixed
     */
    public function getConfig($key = null)
    {
        if ($key !== null) {
            return $this->config[$key];
        }
        return $this->config;
    }

    /**
     * Sends a search request to FINDOLOGIC and returns a XmlResponse object.
     *
     * @throws ServiceNotAliveException if the service is unable to respond.
     * @throws ParamNotSetException if the required params are not set.
     * @return XmlResponse
     */
    public function sendSearchRequest()
    {
        $this->checkRequiredParamsAreSet();

        $this->sendRequest(RequestType::ALIVETEST_REQUEST);
        return new XmlResponse($this->sendRequest(RequestType::SEARCH_REQUEST));
    }

    /**
     * Sends a navigation request to FINDOLOGIC and returns a XmlResponse object.
     *
     * @throws ServiceNotAliveException if the service is unable to respond.
     * @throws ParamNotSetException if the required params are not set.
     * @return XmlResponse
     */
    public function sendNavigationRequest()
    {
        $this->checkRequiredParamsAreSet();

        $this->sendRequest(RequestType::ALIVETEST_REQUEST);
        return new XmlResponse($this->sendRequest(RequestType::NAVIGATION_REQUEST));
    }

    /**
     * Sends a suggestion request to FINDOLOGIC and returns a XmlResponse object.
     *
     * @throws ServiceNotAliveException if the service is unable to respond.
     * @throws ParamNotSetException if the required params are not set.
     * @return JsonResponse
     */
    public function sendSuggestionRequest()
    {
        $this->checkRequiredParamsAreSet();

        $this->sendRequest(RequestType::ALIVETEST_REQUEST);
        return new JsonResponse($this->sendRequest(RequestType::SUGGESTION_REQUEST));
    }

    /**
     * Internal function that is used to send a request. It builds the URL and respects the timeout when sending a
     * request.
     *
     * @param string $requestType Request type that is being used.
     *
     * @return string XmlResponse body.
     * @throws ServiceNotAliveException If the url is unreachable, returns an error message, unexpected body/code or
     * the timeout has been exceeded.
     */
    private function sendRequest($requestType)
    {
        /** @var Client $requestClient */
        $requestClient = $this->getConfig(self::HTTP_CLIENT);
        $requestUrl = $this->buildRequestUrl($requestType);
        $timeout = $this->getRequestTimeout($requestType);

        try {
            $this->startResponseTime();
            $request = $requestClient->request(
                self::GET_METHOD,
                $requestUrl,
                ['connect_timeout' => $timeout]
            );
        } catch (GuzzleException $e) {
            throw new ServiceNotAliveException($e->getMessage());
        }
        $this->endResponseTime();

        $responseBody = $request->getBody();
        $statusCode = $request->getStatusCode();
        $this->checkResponseIsValid($requestType, $responseBody, $statusCode);

        return $responseBody;
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
                throw new ParamNotSetException($paramValue);
            }
        }

        return true;
    }

    /**
     * Internal function for checking if the response is correct for the request type.
     *
     * @param string $requestType
     * @param string $responseBody
     * @param int $statusCode
     *
     * @return bool Returns true on success, otherwise an ServiceNotAliveException will be thrown.
     */
    private function checkResponseIsValid($requestType, $responseBody, $statusCode)
    {
        $isAlivetestRequest = $requestType === RequestType::ALIVETEST_REQUEST;
        $responseBodyIsAlive = $responseBody === self::SERVICE_ALIVE_BODY;
        $httpCodeIsOk = $statusCode === self::STATUS_OK;

        // If it is an alivetest, the 'alive' body needs to be set. If it is not an alivetest, then we do not
        // care about the body. The http code always needs to be 200 OK.
        if (!((($isAlivetestRequest && $responseBodyIsAlive) || !$isAlivetestRequest) && $httpCodeIsOk)) {
            throw new ServiceNotAliveException($responseBody);
        }

        return true;
    }

    /**
     * Sets the unix timestamp in microseconds for the request.
     */
    private function startResponseTime()
    {
        $this->requestUnixTimestamp = microtime(true);
    }

    /**
     * Calculates how much time has been passed since the request has been made to
     * determine the full time duration for the request (in microseconds).
     */
    private function endResponseTime()
    {
        $requestEndTime = microtime(true);
        $this->responseTime = $requestEndTime - $this->requestUnixTimestamp;
    }

    /**
     * Returns the request timeout for the currently used request type.
     *
     * @param $requestType
     * @return int|float
     */
    private function getRequestTimeout($requestType)
    {
        if ($requestType == RequestType::ALIVETEST_REQUEST) {
            return $this->getConfig(self::ALIVETEST_TIMEOUT);
        } else {
            return $this->getConfig(self::REQUEST_TIMEOUT);
        }
    }

    /**
     * @return float
     */
    public function getResponseTime()
    {
        return $this->responseTime;
    }
}
