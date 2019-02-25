<?php

namespace FINDOLOGIC\Api\Tests;

use FINDOLOGIC\Api\Exceptions\ConfigException;
use FINDOLOGIC\Api\FindologicConfig;
use GuzzleHttp\Client;

class FindologicConfigTest extends TestBase
{
    /** @var array */
    private $validConfig = ['shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD'];

    public function testValidFindologicConfigWillWorkAndDefaultsAreFilled()
    {
        $findologicConfig = new FindologicConfig($this->validConfig);
        $this->assertEquals(3.0, $findologicConfig->getRequestTimeout());
        $this->assertEquals(1.0, $findologicConfig->getAlivetestTimeout());
        $this->assertInstanceOf(Client::class, $findologicConfig->getHttpClient());
        $this->assertEquals($this->validConfig['shopkey'], $findologicConfig->getShopkey());
        $this->assertEquals('https://service.findologic.com/ps/%s/%s', $findologicConfig->getApiUrl());
    }

    public function testDefaultConfigCanBeOverridden()
    {
        $expectedRequestTimeout = 10.0;
        $expectedAlivetestTimeout = 5.0;
        $expectedHttpClient = new Client();
        $expectedShopkey = $this->validConfig['shopkey'];
        $expectedApiUrl = 'www.blubbergurken.io/ps/%s/%s';

        $findologicConfig = new FindologicConfig([
            'shopkey' => $expectedShopkey,
            'apiUrl' => $expectedApiUrl,
            'alivetestTimeout' => $expectedAlivetestTimeout,
            'requestTimeout' => $expectedRequestTimeout,
            'httpClient' => $expectedHttpClient,
        ]);

        $this->assertEquals($expectedRequestTimeout, $findologicConfig->getRequestTimeout());
        $this->assertEquals($expectedAlivetestTimeout, $findologicConfig->getAlivetestTimeout());
        $this->assertEquals($expectedHttpClient, $findologicConfig->getHttpClient());
        $this->assertEquals($expectedShopkey, $findologicConfig->getShopkey());
        $this->assertEquals($expectedApiUrl, $findologicConfig->getApiUrl());
    }

    public function invalidConfigProvider()
    {
        return [
            'apiUrl as object' => [[FindologicConfig::API_URL => new \stdClass()]],
            'apiUrl as integer' => [[FindologicConfig::API_URL => 46]],
            'alivetest timeout as object' => [[FindologicConfig::ALIVETEST_TIMEOUT => new \stdClass()]],
            'alivetest timeout as string' => [[FindologicConfig::ALIVETEST_TIMEOUT => 'Timeout of 50 years pls!']],
            'request timeout as object' => [[FindologicConfig::REQUEST_TIMEOUT => new \stdClass()]],
            'request timeout as string' => [[FindologicConfig::REQUEST_TIMEOUT => 'Timeout of 9 quadrillion yrs pls!']],
            'shopkey length not optimal' => [[FindologicConfig::SHOPKEY => 'INVALIDAF']],
            'shopkey has invalid characters' => [[FindologicConfig::SHOPKEY => '80AB18D4BE2654R78244106AD315DC2C']],
            'shopkey is lowercased' => [[FindologicConfig::SHOPKEY => '80ab18d4be2654r78244106ad315dc2c']],
            'shopkey has spaces' => [[FindologicConfig::SHOPKEY => '80AB18D4BE2654A7 8244106AD315DC2C']],
            'shopkey has special characters' => [[FindologicConfig::SHOPKEY => 'AAAAAA.AAAAAAÄAAAAAAAAAAAAAAAAA_']],
        ];
    }

    /**
     * @dataProvider invalidConfigProvider
     * @param $config mixed
     */
    public function testInvalidFindologicConfigThrowsAnException($config)
    {
        try {
            new FindologicConfig($config);
            $this->fail('An invalid FindologicConfig should throw an exception!');
        } catch (ConfigException $e) {
            $this->assertEquals('Invalid config supplied.', $e->getMessage());
        }
    }
}
