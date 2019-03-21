<?php

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Exceptions\ConfigException;
use FINDOLOGIC\Api\Validators\ConfigValidator;
use GuzzleHttp\Client;

class Config
{
    const
        SHOPKEY = 'shopkey',
        API_URL = 'apiUrl',
        ALIVETEST_TIMEOUT = 'alivetestTimeout',
        REQUEST_TIMEOUT = 'requestTimeout',
        HTTP_CLIENT = 'httpClient';

    const
        DEFAULT_TEMPLATE_API_URL = 'https://service.findologic.com/ps/%s/%s',
        DEFAULT_ALIVETEST_TIMEOUT = 1.0,
        DEFAULT_REQUEST_TIMEOUT = 3.0;

    /** @var string */
    private $shopkey;

    /** @var string */
    private $apiUrl;

    /** @var float */
    private $alivetestTimeout;

    /** @var float */
    private $requestTimeout;

    /** @var Client */
    private $httpClient;

    /**
     * @return string
     */
    public function getShopkey()
    {
        if (!$this->shopkey) {
            throw new ConfigException(self::SHOPKEY, 'Required parameter "%s" was not set');
        }

        return $this->shopkey;
    }

    /**
     * @param string $shopkey
     * @return $this
     */
    public function setShopkey($shopkey)
    {
        $validator = new ConfigValidator([self::SHOPKEY => $shopkey]);
        $validator
            ->rule('required', self::SHOPKEY)
            ->rule('shopkey', self::SHOPKEY);

        if (!$validator->validate()) {
            throw new ConfigException(self::SHOPKEY);
        }

        $this->shopkey = $shopkey;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        if (!$this->apiUrl) {
            return self::DEFAULT_TEMPLATE_API_URL;
        }

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
        if (!$this->alivetestTimeout) {
            return self::DEFAULT_ALIVETEST_TIMEOUT;
        }

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
        if (!$this->requestTimeout) {
            return self::DEFAULT_REQUEST_TIMEOUT;
        }

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
    public function setHttpClient($httpClient)
    {
        $validator = new ConfigValidator([self::HTTP_CLIENT => $httpClient]);
        $validator
            ->rule('required', self::HTTP_CLIENT)
            ->rule('httpClient', self::HTTP_CLIENT);

        if (!$validator->validate()) {
            throw new ConfigException(self::HTTP_CLIENT);
        }

        $this->httpClient = $httpClient;
        return $this;
    }
}
