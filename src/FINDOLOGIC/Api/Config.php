<?php

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Exceptions\ConfigException;
use FINDOLOGIC\Api\Validators\ConfigValidator;
use GuzzleHttp\Client;

class Config
{
    const
        SERVICE_ID = 'shopkey',
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
        $validator = new ConfigValidator([self::SERVICE_ID => $serviceId]);
        $validator
            ->rule('required', self::SERVICE_ID)
            ->rule('shopkey', self::SERVICE_ID);

        if (!$validator->validate()) {
            throw new ConfigException(self::SERVICE_ID);
        }

        $this->serviceId = $serviceId;
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
        $validator = new ConfigValidator([self::API_URL => $apiUrl]);
        $validator
            ->rule('required', self::API_URL)
            ->rule('lengthMin', self::API_URL, 5);

        if (!$validator->validate()) {
            throw new ConfigException(self::API_URL);
        }

        $this->apiUrl = $apiUrl;
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
        $validator = new ConfigValidator([self::ALIVETEST_TIMEOUT => $alivetestTimeout]);
        $validator
            ->rule('required', self::ALIVETEST_TIMEOUT)
            ->rule('numeric', self::ALIVETEST_TIMEOUT);

        if (!$validator->validate()) {
            throw new ConfigException(self::ALIVETEST_TIMEOUT);
        }

        $this->alivetestTimeout = $alivetestTimeout;
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
        $validator = new ConfigValidator([self::REQUEST_TIMEOUT => $requestTimeout]);
        $validator
            ->rule('required', self::REQUEST_TIMEOUT)
            ->rule('numeric', self::REQUEST_TIMEOUT);

        if (!$validator->validate()) {
            throw new ConfigException(self::REQUEST_TIMEOUT);
        }

        $this->requestTimeout = $requestTimeout;
        return $this;
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        if (!$this->httpClient) {
            return new Client();
        }

        return $this->httpClient;
    }

    /**
     * @param Client $httpClient
     * @return $this
     */
    public function setHttpClient(Client $httpClient)
    {
        $validator = new ConfigValidator([self::HTTP_CLIENT => $httpClient]);
        $validator->rule('required', self::HTTP_CLIENT);

        if (!$validator->validate()) {
            throw new ConfigException(self::HTTP_CLIENT);
        }

        $this->httpClient = $httpClient;
        return $this;
    }
}
