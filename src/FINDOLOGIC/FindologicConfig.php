<?php

namespace FINDOLOGIC;

use FINDOLOGIC\Exceptions\ConfigException;
use FINDOLOGIC\Validators\ConfigValidator;
use GuzzleHttp\Client;

class FindologicConfig
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

    private $availableConfigKeys = [
        self::SHOPKEY,
        self::API_URL,
        self::ALIVETEST_TIMEOUT,
        self::REQUEST_TIMEOUT,
        self::HTTP_CLIENT,
    ];

    private $defaultConfig = [
        self::SHOPKEY,
        self::API_URL => self::DEFAULT_TEMPLATE_API_URL,
        self::ALIVETEST_TIMEOUT => self::DEFAULT_ALIVETEST_TIMEOUT,
        self::REQUEST_TIMEOUT => self::DEFAULT_REQUEST_TIMEOUT,
        self::HTTP_CLIENT,
    ];

    public function __construct(array $config)
    {
        $this->validateConfig($config);
        $this->setConfig($config);
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
    }

    /**
     * Sets the config attributes
     *
     * @param array $config
     */
    private function setConfig(array $config)
    {
        // Set default httpClient if not explicitly set.
        if (!isset($config[self::HTTP_CLIENT])) {
            $config[self::HTTP_CLIENT] = new Client();
        }

        $configWithDefaults = array_merge($this->defaultConfig, $config);
        foreach ($this->availableConfigKeys as $key) {
            $this->{$key} = $configWithDefaults[$key];
        }
    }

    /**
     * @return string
     */
    public function getShopkey()
    {
        return $this->shopkey;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @return float
     */
    public function getAlivetestTimeout()
    {
        return $this->alivetestTimeout;
    }

    /**
     * @return float
     */
    public function getRequestTimeout()
    {
        return $this->requestTimeout;
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
}
