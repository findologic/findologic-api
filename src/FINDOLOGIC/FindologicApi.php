<?php

namespace FINDOLOGIC;

use FINDOLOGIC\Definitions\RequestType;
use FINDOLOGIC\Exceptions\ConfigException;
use FINDOLOGIC\Exceptions\ParamNotSetException;
use FINDOLOGIC\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Helpers\ParameterBuilder;
use FINDOLOGIC\Objects\JsonResponse;
use FINDOLOGIC\Objects\XmlResponse;
use FINDOLOGIC\Validators\ConfigValidator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use Valitron\Validator;

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
     *          FindologicApi::ALIVETEST_TIMEOUT    => (float) Timeout for an alivetest in seconds. Optional.
     *          FindologicApi::REQUEST_TIMEOUT      => (float) Timeout for a request in seconds. Optional.
     *          FindologicApi::HTTP_CLIENT          => (GuzzleHttp\Client) Client that is used for requests. Optional.
     *      ]
     * @throws ConfigException if the config is not valid.
     */
    public function __construct($config)
    {
        $this->validateConfig($config);

        // Set default httpClient if not explicitly set.
        if (!isset($config[self::HTTP_CLIENT])) {
            $config[self::HTTP_CLIENT] = new Client();
        }

        // Config is validated and defaults are set.
        $this->config = array_merge($this->getConfig(), $config);
    }

    /**
     * Internal function to check whether the config is valid. Returns nothing and will throw an exception if the
     * config is not valid.
     *
     * @param array $config
     * @return bool
     * @throws ConfigException
     */
    private function validateConfig(array $config)
    {
        $validator = new ConfigValidator($config);

        $validator->rule('required', self::SHOPKEY)
            ->rule('shopkey', self::SHOPKEY)
            // TODO: Validate URLs with Valitron if the bug with objects as URLs is fixed.
            ->rule('lengthMin', self::API_URL, 5)
            ->rule('numeric', [self::ALIVETEST_TIMEOUT, self::REQUEST_TIMEOUT])
            ->rule('instanceOf', 'GuzzleHttp\Client', self::HTTP_CLIENT);

        if (!$validator->validate()) {
            throw new ConfigException();
        }

        return true;
    }

    /**
     * Returns the currently set config.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Returns the value for a specific config key. If the config key is unknown, an exception will be thrown.
     *
     * @throws InvalidArgumentException If the config key is unknown or unset.
     * @param string $key
     * @return mixed
     */
    public function getConfigByKey($key)
    {
        if (!isset($this->config[$key])) {
            throw new InvalidArgumentException('Unknown or unset configuration value.');
        } else {
            return $this->config[$key];
        }
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
     * Sends a suggestion request to FINDOLOGIC and returns a JsonResponse object.
     *
     * @throws ServiceNotAliveException if the service is unable to respond.
     * @throws ParamNotSetException if the required params are not set.
     * @return JsonResponse
     */
    public function sendSuggestionRequest()
    {
        $this->checkRequiredParamsAreSet();

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
        $requestClient = $this->getConfigByKey(self::HTTP_CLIENT);
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
        $validator = new Validator($this->params);
        $validator->rule('required', $this->getRequiredParams());

        if (!$validator->validate()) {
            throw new ParamNotSetException(key($validator->errors()));
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
        $responseBodyIsAlive = $responseBody == self::SERVICE_ALIVE_BODY;
        $httpCodeIsOk = $statusCode === self::STATUS_OK;

        if ($isAlivetestRequest) {
            if (!$responseBodyIsAlive) {
                throw new ServiceNotAliveException($responseBody);
            }
        } elseif (!$httpCodeIsOk) {
            throw new ServiceNotAliveException(sprintf('Unexpected status code %s.', $statusCode));
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
            return $this->getConfigByKey(self::ALIVETEST_TIMEOUT);
        } else {
            return $this->getConfigByKey(self::REQUEST_TIMEOUT);
        }
    }

    /**
     * Returns the response time as float in seconds. E.g. 0.1337 seconds. Please note that this is only the
     * time that takes until FINDOLOGIC returns the response.
     *
     * @return float
     */
    public function getResponseTime()
    {
        return $this->responseTime;
    }
}
