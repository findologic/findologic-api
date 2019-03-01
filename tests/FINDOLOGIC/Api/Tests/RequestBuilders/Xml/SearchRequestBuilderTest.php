<?php

namespace FINDOLOGIC\Api\Tests\RequestBuilders\Xml;

use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\FindologicConfig;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;
use FINDOLOGIC\Api\Tests\TestBase;

class SearchRequestBuilderTest extends TestBase
{
    /** @var FindologicConfig */
    private $findologicConfig;

    /** @var string */
    private $mockResponse;

    protected function setUp()
    {
        parent::setUp();
        $this->findologicConfig = new FindologicConfig([
            'shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD',
            'httpClient' => $this->httpClientMock,
        ]);
        $this->mockResponse = $this->getMockResponse('demoResponse.xml');
    }

    public function testSendingRequestsWithoutRequiredParamsWillThrowAnException()
    {
        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        try {
            $searchRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the shopurl param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param shopurl is not set.', $e->getMessage());
        }

        $searchRequestBuilder->setShopurl('blubbergurken.io');
        try {
            $searchRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the userip param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param userip is not set.', $e->getMessage());
        }

        $searchRequestBuilder->setUserip('127.0.0.1');
        try {
            $searchRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the referer param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param referer is not set.', $e->getMessage());
        }

        $searchRequestBuilder->setReferer('https://blubbergurken.io/blubbergurken-sale/');
        try {
            $searchRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the revision param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param revision is not set.', $e->getMessage());
        }

        $searchRequestBuilder->setRevision('2.5.10');
        try {
            $searchRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the query param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param query is not set.', $e->getMessage());
        }

        $this->httpClientMock->method('request')->willReturn($this->responseMock);
        $this->responseMock->method('getBody')->willReturn($this->streamMock);
        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->streamMock->method('getContents')
            ->willReturnOnConsecutiveCalls(
                'alive',
                'alive',
                $this->mockResponse,
                $this->mockResponse
            );

        $searchRequestBuilder->setQuery('');
        $searchRequestBuilder->sendRequest();
    }

    public function stringProvider()
    {
        return [
            'some random string can be set' => [
                'string' => 'something',
                'expectedResult' => 'something',
            ],
            'an empty string an be set' => [
                'string' => '',
                'expectedResult' => '',
            ],
            'special characters in string should be url encoded' => [
                'string' => '/ /',
                'expectedResult' => '%2F+%2F',
            ],
        ];
    }

    /**
     * @dataProvider stringProvider
     * @param string $string
     * @param string $expectedResult
     */
    public function testSetQueryCanBeSetAndIsFormattedAsQueryString($string, $expectedResult)
    {
        $expectedParameter = sprintf('&query=%s', $expectedResult);

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setQuery($string);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    public function invalidQueryProvider()
    {
        return [
            'integer as query' => [
                'query' => 21,
            ],
            'object as query' => [
                'query' => new \stdClass(),
            ],
            'float as query' => [
                'query' => 3.1415,
            ],
        ];
    }

    /**
     * @dataProvider invalidQueryProvider
     * @param mixed $invalidQuery
     */
    public function testSetQueryWillThrowAnExceptionWhenSubmittingInvalidQueries($invalidQuery)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter query is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setQuery($invalidQuery);
    }

    public function shopkeyProvider()
    {
        return [
            'some random shopkey can be set' => [
                'expectedShopkey' => '80AB18D4BE2654A78244106AD315DC2C',
            ],
            'some different shopkey can be set' => [
                'expectedShopkey' => 'AAAABBBBCCCC1234AAAABBBBCCCC1234',
            ],
            'some completely different shopkey can be set' => [
                'expectedShopkey' => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
            ],
        ];
    }

    /**
     * @dataProvider shopkeyProvider
     * @param string $expectedShopkey
     */
    public function testSetShopkeyCanBeSetAndIsFormattedAsQueryString($expectedShopkey)
    {
        $expectedParameter = sprintf('&shopkey=%s', $expectedShopkey);

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setShopkey($expectedShopkey);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    public function invalidShopkeyProvider()
    {
        return [
            'shopkey is not a shopkey' => ['invalidShopkey'],
            'shopkey is an integer' => [5],
            'shopkey is an array' => [['80AB18D4BE2654A78244106AD315DC2C']],
            'shopkey is an object' => [new \stdClass()],
            'shopkey length not optimal' => ['INVALIDAF'],
            'shopkey contains invalid characters' => ['80AB18D4BE2654R78244106AD315DC2C'],
            'shopkey is lowercased' => ['80ab18d4be2654r78244106ad315dc2c'],
            'shopkey contains spaces' => ['80AB18D4BE2654A7 8244106AD315DC2C'],
            'shopkey contains special characters' => ['AAAAAA.AAAAAAÄAAAAAAAAAAAAAAAAA_'],
        ];
    }

    /**
     * @dataProvider invalidShopkeyProvider
     * @param string $invalidShopkey
     */
    public function testInvalidShopkeyWillThrowAnException($invalidShopkey)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter shopkey is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setShopkey($invalidShopkey);
    }

    public function testShopkeyIsAutomaticallyAddedFromTheConfigIfNotOverridden()
    {
        $expectedParameter = sprintf('&shopkey=%s', $this->findologicConfig->getShopkey());

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    public function shopurlProvider()
    {
        return [
            'normal shopurl' => [
                'shopurl' => 'www.poop.com',
                'expectedResult' => 'www.poop.com',
            ],
            'other shopurl' => [
                'shopurl' => 'www.shop.co.de',
                'expectedResult' => 'www.shop.co.de',
            ],
            'more different shopurl' => [
                'shopurl' => 'blubbergurken.de/shop',
                'expectedResult' => 'blubbergurken.de%2Fshop',
            ]
        ];
    }

    /**
     * @dataProvider shopurlProvider
     * @param string $shopurl
     * @param string $expectedResult
     */
    public function testShopurlCanBeSetAndIsFormattedAsQueryString($shopurl, $expectedResult)
    {
        $expectedParameter = sprintf('?shopurl=%s', $expectedResult);

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setShopurl($shopurl);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    public function invalidShopurlProvider()
    {
        return [
            'shopurl is not an url' => ['invalidShopurl'],
            'shopurl is an integer' => [5],
            'shopurl is an array' => [['https://validurl.com/but/is/an/array']],
            'shopurl is an object' => [new \stdClass()],
            'shopurl with missing slashes after protocol' => ['http:www.example.com/main.html'],
        ];
    }

    /**
     * @dataProvider invalidShopurlProvider
     * @param string $invalidShopurl
     */
    public function testInvalidShopurlWillThrowAnException($invalidShopurl)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter shopurl is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setShopurl($invalidShopurl);
    }

    public function useripProvider()
    {
        return [
            'normal userip' => [
                'userip' => '127.0.0.1',
                'expectedResult' => '127.0.0.1',
            ],
            'other userip' => [
                'userip' => '183.12.42.33',
                'expectedResult' => '183.12.42.33',
            ],
            'more different userip' => [
                'userip' => '255.255.255.255',
                'expectedResult' => '255.255.255.255',
            ],
            'ipv6 userip' => [
                'userip' => '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
                'expectedResult' => '2001%3A0db8%3A85a3%3A0000%3A0000%3A8a2e%3A0370%3A7334',
            ],
        ];
    }

    /**
     * @dataProvider useripProvider
     * @param string $userip
     * @param string $expectedResult
     */
    public function testUseripCanBeSetAndIsFormattedAsQueryString($userip, $expectedResult)
    {
        $expectedParameter = sprintf('&userip=%s', $expectedResult);

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setUserip($userip);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    public function invalidUseripProvider()
    {
        return [
            'userip is not an ip' => ['invalidIp'],
            'userip is an integer' => [5],
            'userip is an array' => [['127.0.0.1']],
            'userip is an object' => [new \stdClass()],
            'userip with too many numbers' => ['1.10.100.1000'],
        ];
    }

    /**
     * @dataProvider invalidUseripProvider
     * @param string $invalidUserip
     */
    public function testInvalidUseripWillThrowAnException($invalidUserip)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter userip is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setUserip($invalidUserip);
    }

    /**
     * Returns some referer that might be set by users.
     *
     * @return array
     */
    public function refererProvider()
    {
        return [
            'normal referer' => [
                'referer' => 'https://www.somedomain.de/best-category',
                'expectedResult' => 'https%3A%2F%2Fwww.somedomain.de%2Fbest-category',
            ],
            'other referer' => [
                'referer' => 'http://this-is-an-unsecure-domain.com/unsecure',
                'expectedResult' => 'http%3A%2F%2Fthis-is-an-unsecure-domain.com%2Funsecure',
            ],
            'more different referer' => [
                'referer' => 'http://www.domain.ru/domains',
                'expectedResult' => 'http%3A%2F%2Fwww.domain.ru%2Fdomains',
            ]
        ];
    }

    /**
     * @dataProvider refererProvider
     * @param string $referer
     * @param string $expectedResult
     */
    public function testRefererCanBeSetAndIsFormattedAsQueryString($referer, $expectedResult)
    {
        $expectedParameter = sprintf('&referer=%s', $expectedResult);

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setReferer($referer);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    public function invalidRefererProvider()
    {
        return [
            'referer is not an ip' => ['invalidIp'],
            'referer is an integer' => [5],
            'referer is an array' => [['127.0.0.1']],
            'referer is an object' => [new \stdClass()],
            'referer with wrong protocol call' => ['http::///www.domain.ru/domains'],
        ];
    }

    /**
     * @dataProvider invalidRefererProvider
     * @param string $invalidReferer
     */
    public function testInvalidRefererWillThrowAnException($invalidReferer)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter referer is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setReferer($invalidReferer);
    }

    /**
     * Returns some revision that might be set by users.
     *
     * @return array
     */
    public function revisionProvider()
    {
        return [
            'normal revision' => ['1.0.0'],
            'other revision' => ['5.1.3'],
            'more different revision' => ['55.3.11']
        ];
    }

    /**
     * @dataProvider revisionProvider
     * @param string $revision
     */
    public function testRevisionCanBeSetAndIsFormattedAsQueryString($revision)
    {
        $expectedParameter = sprintf('&revision=%s', $revision);

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setRevision($revision);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    public function invalidRevisionProvider()
    {
        return [
            'revision is not a revision' => ['invalidRevision'],
            'revision is an integer' => [5],
            'revision is an array' => [['1.0.0']],
            'revision is an object' => [new \stdClass()],
            'revision with a dot after the number' => ['1.'],
        ];
    }

    /**
     * @dataProvider invalidRevisionProvider
     * @param string $invalidRevision
     */
    public function testInvalidRevisionWillThrowAnException($invalidRevision)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter revision is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setRevision($invalidRevision);
    }

    public function attributeProvider()
    {
        return [
            'normal attribute' => ['vendor', 'TomTailor', null],
            'other attribute' => ['Material', 'Leather', null],
            'more different attribute' => ['price', '200.0', 'max']
        ];
    }

    /**
     * @dataProvider attributeProvider
     * @param string $expectedAttributeName
     * @param string $expectedAttributeValue
     * @param string $specifier
     */
    public function testSetAttributeWillSetItInAValidFormat(
        $expectedAttributeName,
        $expectedAttributeValue,
        $specifier
    ) {
        $expectedParameter = sprintf(
            // Decoded this looks attrib[%s][%s]=%s
            '&attrib%%5B%s%%5D%%5B%s%%5D=%s',
            $expectedAttributeName,
            $specifier,
            $expectedAttributeValue
        );

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addAttribute($expectedAttributeName, $expectedAttributeValue, $specifier);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    public function invalidAttributeProvider()
    {
        return [
            'attribute name is an array' => [['price'], '50', null],
            'attribute value is an array' => ['price', ['50'], null],
            'attribute specifier is an array' => ['price', '50', [null]],
            'attribute name is an integer' => [5, '50', null],
            'attribute specifier is an integer' => ['price', '50', 2],
            'attribute name is an object' => [new \stdClass(), '50', null],
            'attribute value is an object' => ['price', new \stdClass(), null],
            'attribute specifier is an object' => ['price', '50', new \stdClass()],
        ];
    }

    /**
     * @dataProvider invalidAttributeProvider
     * @param $expectedAttributeName
     * @param $expectedAttributeValue
     * @param $specifier
     */
    public function testInvalidAttributeWillThrowAnException(
        $expectedAttributeName,
        $expectedAttributeValue,
        $specifier
    ) {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter attrib is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addAttribute($expectedAttributeName, $expectedAttributeValue, $specifier);
    }
}
