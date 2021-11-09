<?php

namespace FINDOLOGIC\Api;

use FINDOLOGIC\Api\Exceptions\ConfigException;
use FINDOLOGIC\Api\Validators\ConfigValidator;
use FINDOLOGIC\GuzzleHttp\Client;

class Config
{
    public const DEFAULT_TEMPLATE_API_URL = 'https://service.findologic.com/ps/%s/%s';
    public const DEFAULT_ALIVETEST_TIMEOUT = 1.0;
    public const DEFAULT_REQUEST_TIMEOUT = 3.0;

    private const SERVICE_ID = 'serviceId';
    private const API_URL = 'apiUrl';
    private const ALIVETEST_TIMEOUT = 'alivetestTimeout';
    private const REQUEST_TIMEOUT = 'requestTimeout';
    private const HTTP_CLIENT = 'httpClient';
    private const ACCESS_TOKEN = 'accessToken';

    private string $serviceId = '';
    private Client $httpClient;

    private ?string $accessToken = null;
    private string $apiUrl = self::DEFAULT_TEMPLATE_API_URL;
    private float $alivetestTimeout = self::DEFAULT_ALIVETEST_TIMEOUT;
    private float $requestTimeout = self::DEFAULT_REQUEST_TIMEOUT;

    public function __construct($serviceId = null)
    {
        if ($serviceId) {
            $this->setServiceId($serviceId);
        }

        $this->httpClient = new Client();
    }

    /**
     * Sets a specified config value and validates them according to the given validation rules.
     *
     * @param mixed $value
     * @param array $validationRules
     */
    private function setConfigValue(string $key, $value, array $validationRules): void
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

    public function getServiceId(): string
    {
        if (!$this->serviceId) {
            throw new ConfigException(self::SERVICE_ID, 'Required parameter "%s" was not set');
        }

        return $this->serviceId;
    }

    public function setServiceId($serviceId): self
    {
        $this->setConfigValue(self::SERVICE_ID, $serviceId, ['required', 'shopkey']);

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->setConfigValue(self::ACCESS_TOKEN, $accessToken, ['required']);

        return $this;
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    public function setApiUrl(string $apiUrl): self
    {
        $this->setConfigValue(self::API_URL, $apiUrl, ['required']);

        return $this;
    }

    public function getAlivetestTimeout(): float
    {
        return $this->alivetestTimeout;
    }

    public function setAlivetestTimeout(float $alivetestTimeout): self
    {
        $this->setConfigValue(self::ALIVETEST_TIMEOUT, $alivetestTimeout, ['required', 'numeric']);

        return $this;
    }

    public function getRequestTimeout(): float
    {
        return $this->requestTimeout;
    }

    public function setRequestTimeout(float $requestTimeout): self
    {
        $this->setConfigValue(self::REQUEST_TIMEOUT, $requestTimeout, ['required', 'numeric']);

        return $this;
    }

    /**
     * If not explicitly overridden, will return a Guzzle client that's used for sending requests.
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    public function setHttpClient(Client $httpClient): self
    {
        $this->setConfigValue(self::HTTP_CLIENT, $httpClient, ['required']);

        return $this;
    }
}
