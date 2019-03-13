<?php

namespace FINDOLOGIC\Api\Tests;

use FINDOLOGIC\Api\Exceptions\ServiceNotAliveException;
use FINDOLOGIC\Api\Client;
use FINDOLOGIC\Api\Config;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class ClientTest extends TestBase
{
    /** @var string */
    private $validShopkey = 'ABCDABCDABCDABCDABCDABCDABCDABCD';

    /** @var Config */
    private $findologicConfig;

    protected function setUp()
    {
        parent::setUp();

        $this->findologicConfig = new Config([
            'shopkey' => $this->validShopkey,
            'httpClient' => $this->httpClientMock,
        ]);
    }

    public function testRequestIsBeingCalledWithExpectedParameters()
    {
        $expectedRequestUrl = 'https://secure.blubbergurken.io/autocomplete.php?query=wut&shopkey=something';
        $expectedBody = '[]';

        $this->setExpectationsForRequests($expectedRequestUrl, $expectedBody);

        $findologicClient = new Client($this->findologicConfig);
        $result = $findologicClient->request($expectedRequestUrl);

        $this->assertSame($expectedBody, $result);
    }

    public function badStatusCodeProvider()
    {
        return [
            [500],
            [501],
            [502],
            [503],
            [504],
            [505],
            [400],
            [401],
            [402],
            [403],
            [404],
            [405],
            [406],
            [300],
            [201],
            [102],
        ];
    }

    /**
     * @dataProvider badStatusCodeProvider
     * @param int $statusCode
     */
    public function testRequestWillThrowAnExceptionIfItHasAnUnexpectedStatusCode($statusCode)
    {
        $expectedRequestUrl = 'https://secure.blubbergurken.io/autocomplete.php?query=hut&shopkey=something';
        $expectedBody = json_encode([
            'label' => 'schuljahr',
            'block' => 'suggest',
            'frequency' => '3463',
            'imageUrl' => null,
            'price' => null,
            'identifier' => null,
            'basePrice' => null,
            'basePriceUnit' => null,
            'url' => null,
        ]);

        $this->setExpectationsForRequests($expectedRequestUrl, $expectedBody, $statusCode);

        $findologicClient = new Client($this->findologicConfig);

        try {
            $findologicClient->request($expectedRequestUrl);
            $this->fail('An ServiceNotAliveException should be thrown if the status code is not OK.');
        } catch (ServiceNotAliveException $e) {
            $this->assertEquals(sprintf(
                'The service is not alive. Reason: Unexpected status code %s.',
                $statusCode
            ), $e->getMessage());
        }
    }

    public function testAliveTestRequestWillBeCalledWithLessTimeout()
    {
        $expectedRequestUrl = 'https://secure.blubbergurken.io/alivetest.php';
        $expectedBody = 'alive';

        $this->setExpectationsForAliveTestRequests($expectedRequestUrl, $expectedBody);

        $findologicClient = new Client($this->findologicConfig);
        $result = $findologicClient->request($expectedRequestUrl, true);

        $this->assertSame($expectedBody, $result);
    }

    public function badAliveTestBodies()
    {
        return [
            [' alive'],
            ['alive '],
            ['alive\n'],
            ['\nalive'],
            ['it could be alive'],
            ['the service is alive'],
            ['i am more dead than death himself, so lets say I am alive'],
            ['rip'],
            ['i am ded'],
            ['<h1>alive</h1>'],
            ['<span>alive</span>'],
        ];
    }

    /**
     * @dataProvider badAliveTestBodies
     * @param string $expectedBody
     */
    public function testExceptionIsThrownIfAliveTestBodyIsSomethingElseThenAlive($expectedBody)
    {
        $expectedRequestUrl = 'https://secure.blubbergurken.io/alivetest.php';

        $this->setExpectationsForAliveTestRequests($expectedRequestUrl, $expectedBody);

        $findologicClient = new Client($this->findologicConfig);
        try {
            $findologicClient->request($expectedRequestUrl, true);
            $this->fail('An exception should be thrown if the alivetest returns something else then "alive"');
        } catch (ServiceNotAliveException $e) {
            $this->assertEquals(sprintf(
                'The service is not alive. Reason: %s',
                $expectedBody
            ), $e->getMessage());
        }
    }

    public function testWhenDoingAnAliveTestTheResponseTimeIsNull()
    {
        $expectedRequestUrl = 'https://secure.blubbergurken.io/alivetest.php';
        $expectedBody = 'alive';

        $this->setExpectationsForAliveTestRequests($expectedRequestUrl, $expectedBody);

        $findologicClient = new Client($this->findologicConfig);
        $findologicClient->request($expectedRequestUrl, true);

        $this->assertSame(null, $findologicClient->getResponseTime());
    }

    public function testWhenDoingARequestTheResponseTimeShouldBeSet()
    {
        $expectedRequestUrl = 'https://secure.blubbergurken.io/autocomplete.php?query=wut&shopkey=something';
        $expectedBody = '[]';

        $this->setExpectationsForRequests($expectedRequestUrl, $expectedBody);

        $findologicClient = new Client($this->findologicConfig);
        $findologicClient->request($expectedRequestUrl);

        // Local response time should be fast since the data will not be sent to another server, but instead it
        // will be directly read from the ram.
        $this->assertEquals(0, $findologicClient->getResponseTime(), '', 0.01);
    }

    public function testWhenGuzzleFailsWillThrowAnException()
    {
        $expectedExceptionMessage = 'Guzzle is dying. Maybe it can be saved with a heart massage.';
        $expectedRequestUrl = 'https://secure.blubbergurken.io/autocomplete.php?query=wut&shopkey=something';

        $this->httpClientMock->method('request')
            ->with('GET', $expectedRequestUrl, ['connect_timeout' => 3.0])
            ->willThrowException(new RequestException(
                $expectedExceptionMessage,
                new Request('GET', $expectedRequestUrl)
            ));

        $findologicClient = new Client($this->findologicConfig);

        try {
            $findologicClient->request($expectedRequestUrl);
            $this->fail('If Guzzle throws an exception it should be caught by us and thrown that something is wrong.');
        } catch (ServiceNotAliveException $e) {
            $this->assertEquals(sprintf(
                'The service is not alive. Reason: %s',
                $expectedExceptionMessage
            ), $e->getMessage());
        }
    }
}
