<?php

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Exceptions\ConfigException;
use FINDOLOGIC\Api\Validators\ConfigValidator;
use GuzzleHttp\Client as GuzzleClient;

class Config
{
    const
        SERVICE_ID = 'serviceId',
        API_URL = 'apiUrl',
        ALIVETEST_TIMEOUT = 'alivetestTimeout',
        REQUEST_TIMEOUT = 'requestTimeout',
        HTTP_CLIENT = 'httpClient';

    const
        DEFAULT_TEMPLATE_API_URL = 'https://service.findologic.com/ps/%s/%s',
        DEFAULT_ALIVETEST_TIMEOUT = 1.0,
        DEFAULT_REQUEST_TIMEOUT = 3.0;

    /** @var string */
    private $serviceId;

    /** @var string */
    private $apiUrl = self::DEFAULT_TEMPLATE_API_URL;

    /** @var float */
    private $alivetestTimeout = self::DEFAULT_ALIVETEST_TIMEOUT;

    /** @var float */
    private $requestTimeout = self::DEFAULT_REQUEST_TIMEOUT;

    /** @var Client */
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new GuzzleClient();
    }

    /**
     * Sets a specified config value and validates them according to the given validation rules.
     *
     * @param string $key
     * @param mixed $value
     * @param array $validationRules
     */
    private function setConfigValue($key, $value, array $validationRules)
    {
        $validator = new ConfigValidator([$key => $value]);

        foreach ($validationRules as $rule) {
            $validator->rule($rule, $key);
        }

        if (!$validator->validate()) {
            throw new ConfigException($key);
        }

        $this->{$key} = $value;
    }

    /**
     * @return string
     */
    public function getServiceId()
    {
        if (!$this->serviceId) {
            throw new ConfigException(self::SERVICE_ID, 'Required parameter "%s" was not set');
        }

        return $this->serviceId;
    }

    /**
     * @param string $serviceId
     * @return $this
     */
    public function setServiceId($serviceId)
    {
        $this->setConfigValue(self::SERVICE_ID, $serviceId, ['required', 'shopkey']);
        return $this;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     * @return $this
     */
    public function setApiUrl($apiUrl)
    {
        $this->setConfigValue(self::API_URL, $apiUrl, ['required']);
        return $this;
    }

    /**
     * @return float
     */
    public function getAlivetestTimeout()
    {
        return $this->alivetestTimeout;
    }

    /**
     * @param float $alivetestTimeout
     * @return $this
     */
    public function setAlivetestTimeout($alivetestTimeout)
    {
        $this->setConfigValue(self::ALIVETEST_TIMEOUT, $alivetestTimeout, ['required', 'numeric']);
        return $this;
    }

    /**
     * @return float
     */
    public function getRequestTimeout()
    {
        return $this->requestTimeout;
    }

    /**
     * @param float $requestTimeout
     * @return $this
     */
    public function setRequestTimeout($requestTimeout)
    {
        $this->setConfigValue(self::REQUEST_TIMEOUT, $requestTimeout, ['required', 'numeric']);
        return $this;
    }

    /**
     * If not explicitly overridden, will return a Guzzle client that's used for sending requests.
     *
     * @return GuzzleClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param GuzzleClient $httpClient
     * @return $this
     */
    public function setHttpClient(GuzzleClient $httpClient)
    {
        $this->setConfigValue(self::HTTP_CLIENT, $httpClient, ['required']);
        return $this;
    }
}
