<?php

namespace FINDOLOGIC\Api\Tests;

use FINDOLOGIC\Api\Exceptions\ConfigException;
use FINDOLOGIC\Api\Config;
use GuzzleHttp\Client;
use TypeError;

class ConfigTest extends TestBase
{
    /** @var array */
    private $validConfig = ['shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD'];

    public function testValidConfigWillWorkAndDefaultsAreFilled()
    {
        $config = new Config();
        $config->setServiceId($this->validConfig['shopkey']);
        $this->assertEquals(3.0, $config->getRequestTimeout());
        $this->assertEquals(1.0, $config->getAlivetestTimeout());
        $this->assertInstanceOf(Client::class, $config->getHttpClient());
        $this->assertEquals($this->validConfig['shopkey'], $config->getServiceId());
        $this->assertEquals('https://service.findologic.com/ps/%s/%s', $config->getApiUrl());
    }

    public function testDefaultConfigCanBeOverridden()
    {
        $expectedRequestTimeout = 10.0;
        $expectedAlivetestTimeout = 5.0;
        $expectedHttpClient = new Client();
        $expectedShopkey = $this->validConfig['shopkey'];
        $expectedApiUrl = 'www.blubbergurken.io/ps/%s/%s';

        $config = new Config();
        $config
            ->setServiceId($expectedShopkey)
            ->setApiUrl($expectedApiUrl)
            ->setAlivetestTimeout($expectedAlivetestTimeout)
            ->setRequestTimeout($expectedRequestTimeout)
            ->setHttpClient($expectedHttpClient);

        $this->assertEquals($expectedRequestTimeout, $config->getRequestTimeout());
        $this->assertEquals($expectedAlivetestTimeout, $config->getAlivetestTimeout());
        $this->assertEquals($expectedHttpClient, $config->getHttpClient());
        $this->assertEquals($expectedShopkey, $config->getServiceId());
        $this->assertEquals($expectedApiUrl, $config->getApiUrl());
    }

    public function invalidConfigProvider()
    {
        return [
            'httpClient as some object' => [['httpClient' => new \stdClass()]],
            'httpClient as integer' => [['httpClient' => 46]],
            'alivetest timeout as object' => [['alivetestTimeout' => new \stdClass()]],
            'alivetest timeout as string' => [['alivetestTimeout' => 'Timeout of 50 years pls!']],
            'request timeout as object' => [['requestTimeout' => new \stdClass()]],
            'request timeout as string' => [['requestTimeout' => 'Timeout of 9 quadrillion yrs pls!']],
            'shopkey length not optimal' => [['shopkey'=> 'INVALIDAF']],
            'shopkey has invalid characters' => [['shopkey' => '80AB18D4BE2654R78244106AD315DC2C']],
            'shopkey is lowercased' => [['shopkey' => '80ab18d4be2654r78244106ad315dc2c']],
            'shopkey has spaces' => [['shopkey' => '80AB18D4BE2654A7 8244106AD315DC2C']],
            'shopkey has special characters' => [['shopkey' => 'AAAAAA.AAAAAAÄAAAAAAAAAAAAAAAAA_']],
        ];
    }

    /**
     * @dataProvider invalidConfigProvider
     * @param $config mixed
     */
    public function testInvalidConfigThrowsAnException($config)
    {
        try {
            $configObj = new Config();
            if (isset($config['apiUrl'])) {
                $configObj->setApiUrl($config['apiUrl']);
            }
            if (isset($config['alivetestTimeout'])) {
                $configObj->setAlivetestTimeout($config['alivetestTimeout']);
            }
            if (isset($config['requestTimeout'])) {
                $configObj->setRequestTimeout($config['requestTimeout']);
            }
            if (isset($config['shopkey'])) {
                $configObj->setServiceId($config['shopkey']);
            }
            if (isset($config['httpClient'])) {
                $configObj->setHttpClient($config['httpClient']);
            }

            $this->fail('An invalid Config should throw an exception!');
        } catch (ConfigException $e) {
            $this->assertStringStartsWith('Config parameter', $e->getMessage());
        } catch (TypeError $e) {
            $this->assertStringStartsWith('Argument 1 passed to', $e->getMessage());
        }
    }

    public function testGetShopkeyWillThrowAnExceptionIfItWasNotSetBefore()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Required parameter "serviceId" was not set');

        $config = new Config();
        $config->getServiceId();
    }
}
