<?php

namespace FINDOLOGIC\Api\Tests;

use FINDOLOGIC\Api\Exceptions\ConfigException;
use FINDOLOGIC\Api\Config;
use GuzzleHttp\Client;

class ConfigTest extends TestBase
{
    /** @var array */
    private $validConfig = ['shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD'];

    public function testValidConfigWillWorkAndDefaultsAreFilled()
    {
        $config = new Config($this->validConfig);
        $this->assertEquals(3.0, $config->getRequestTimeout());
        $this->assertEquals(1.0, $config->getAlivetestTimeout());
        $this->assertInstanceOf(Client::class, $config->getHttpClient());
        $this->assertEquals($this->validConfig['shopkey'], $config->getShopkey());
        $this->assertEquals('https://service.findologic.com/ps/%s/%s', $config->getApiUrl());
    }

    public function testDefaultConfigCanBeOverridden()
    {
        $expectedRequestTimeout = 10.0;
        $expectedAlivetestTimeout = 5.0;
        $expectedHttpClient = new Client();
        $expectedShopkey = $this->validConfig['shopkey'];
        $expectedApiUrl = 'www.blubbergurken.io/ps/%s/%s';

        $config = new Config([
            'shopkey' => $expectedShopkey,
            'apiUrl' => $expectedApiUrl,
            'alivetestTimeout' => $expectedAlivetestTimeout,
            'requestTimeout' => $expectedRequestTimeout,
            'httpClient' => $expectedHttpClient,
        ]);

        $this->assertEquals($expectedRequestTimeout, $config->getRequestTimeout());
        $this->assertEquals($expectedAlivetestTimeout, $config->getAlivetestTimeout());
        $this->assertEquals($expectedHttpClient, $config->getHttpClient());
        $this->assertEquals($expectedShopkey, $config->getShopkey());
        $this->assertEquals($expectedApiUrl, $config->getApiUrl());
    }

    public function invalidConfigProvider()
    {
        return [
            'apiUrl as object' => [['apiUrl' => new \stdClass()]],
            'apiUrl as integer' => [['apiUrl' => 46]],
            'alivetest timeout as object' => [['alivetestTimeout' => new \stdClass()]],
            'alivetest timeout as string' => [['alivetestTimeout' => 'Timeout of 50 years pls!']],
            'request timeout as object' => [['requestTimeout' => new \stdClass()]],
            'request timeout as string' => [['requestTimeout' => 'Timeout of 9 quadrillion yrs pls!']],
            'shopkey length not optimal' => [['shopkey'=> 'INVALIDAF']],
            'shopkey has invalid characters' => [['shopkey' => '80AB18D4BE2654R78244106AD315DC2C']],
            'shopkey is lowercased' => [['shopkey' => '80ab18d4be2654r78244106ad315dc2c']],
            'shopkey has spaces' => [['shopkey' => '80AB18D4BE2654A7 8244106AD315DC2C']],
            'shopkey has special characters' => [['shopkey' => 'AAAAAA.AAAAAAÄAAAAAAAAAAAAAAAAA_']],
            'config is empty' => [[]],
            'everything is set except for shopkey' => [[
                'apiUrl' => 'https://service.findologic.com/ps/%s/%s',
                'alivetestTimeout' => 12.34,
                'requestTimeout' => 56.78,
                'httpClient' => new Client(),
            ]],
        ];
    }

    /**
     * @dataProvider invalidConfigProvider
     * @param $config mixed
     */
    public function testInvalidConfigThrowsAnException($config)
    {
        try {
            new Config($config);
            $this->fail('An invalid Config should throw an exception!');
        } catch (ConfigException $e) {
            $this->assertEquals('Invalid config supplied.', $e->getMessage());
        }
    }
}
