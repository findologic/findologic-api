<?php

namespace FINDOLOGIC\Api\Tests\RequestBuilders\Xml;

use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\FindologicConfig;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;
use FINDOLOGIC\Api\Tests\TestBase;

/**
 * This class is testing the request builders for search and navigation requests. Most tests are using the
 * SearchRequestBuilder since navigation is basically the same except that query is not a required param for
 * navigation requests.
 */
class XmlRequestBuilderTest extends TestBase
{
    use XmlRequestDataProvider;

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

    /**
     * @dataProvider queryProvider
     * @param string $query
     * @param string $expectedResult
     */
    public function testSetQueryCanBeSetAndIsFormattedAsQueryString($query, $expectedResult)
    {
        $expectedParameter = sprintf('&query=%s', $expectedResult);

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setQuery($query);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
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
            // Decoded this looks like &attrib[%s][%s]=%s
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

    /**
     * @dataProvider orderProvider
     * @param string $expectedOrder
     * @param string $expectedResult
     */
    public function testSetOrderWillSetItInAValidFormat($expectedOrder, $expectedResult)
    {
        $expectedParameter = sprintf('&order=%s', $expectedResult);

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setOrder($expectedOrder);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidOrderProvider
     * @param string $invalidOrder
     */
    public function testInvalidOrderWillThrowAnException($invalidOrder)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter order is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setOrder($invalidOrder);
    }

    /**
     * @dataProvider orderProvider
     * @param string $expectedProperty
     * @param string $expectedResult
     */
    public function testSetPropertyWillSetItInAValidFormat($expectedProperty, $expectedResult)
    {
        $expectedParameter = sprintf('&property=%s', $expectedResult);

        $searchRequestBuilder = new SearchRequestBuilder($this->findologicConfig);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setOrder($expectedProperty);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }
}
