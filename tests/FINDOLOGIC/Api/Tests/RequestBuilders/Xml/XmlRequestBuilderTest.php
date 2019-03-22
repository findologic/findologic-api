<?php

namespace FINDOLOGIC\Api\Tests\RequestBuilders\Xml;

use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\RequestBuilders\Xml\NavigationRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;
use FINDOLOGIC\Api\Tests\TestBase;
use InvalidArgumentException;

/**
 * This class is testing the request builders for search and navigation requests. Most tests are using the
 * SearchRequestBuilder since navigation is basically the same except that query is not a required param for
 * navigation requests.
 */
class XmlRequestBuilderTest extends TestBase
{
    use XmlRequestDataProvider;

    /** @var Config */
    private $config;

    /** @var string */
    private $rawMockResponse;

    protected function setUp()
    {
        parent::setUp();
        $this->config = new Config();
        $this->config
            ->setServiceId('ABCDABCDABCDABCDABCDABCDABCDABCD')
            ->setHttpClient($this->httpClientMock);

        $this->rawMockResponse = $this->getMockResponse('demoResponse.xml');
    }

    public function testSendingSearchRequestsWithoutRequiredParamsWillThrowAnException()
    {
        $this->httpClientMock->method('request')->willReturn($this->responseMock);
        $this->responseMock->method('getBody')->willReturn($this->streamMock);
        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->streamMock->method('getContents')
            ->willReturnOnConsecutiveCalls(
                'alive',
                'alive',
                $this->rawMockResponse,
                $this->rawMockResponse
            );

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
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

        $searchRequestBuilder->setQuery('');
        $response = $searchRequestBuilder->sendRequest();
        $this->assertEquals(0, $response->getResponseTime(), '', 0.1);
    }

    public function testSendingNavigationRequestsWithoutRequiredParamsWillThrowAnException()
    {
        $this->httpClientMock->method('request')->willReturn($this->responseMock);
        $this->responseMock->method('getBody')->willReturn($this->streamMock);
        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->streamMock->method('getContents')
            ->willReturnOnConsecutiveCalls(
                'alive',
                'alive',
                $this->rawMockResponse,
                $this->rawMockResponse
            );

        $navigationRequestBuilder = new NavigationRequestBuilder($this->config);
        try {
            $navigationRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the shopurl param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param shopurl is not set.', $e->getMessage());
        }

        $navigationRequestBuilder->setShopurl('blubbergurken.io');
        try {
            $navigationRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the userip param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param userip is not set.', $e->getMessage());
        }

        $navigationRequestBuilder->setUserip('127.0.0.1');
        try {
            $navigationRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the referer param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param referer is not set.', $e->getMessage());
        }

        $navigationRequestBuilder->setReferer('https://blubbergurken.io/blubbergurken-sale/');
        try {
            $navigationRequestBuilder->sendRequest();
            $this->fail('An exception was expected to happen if the revision param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param revision is not set.', $e->getMessage());
        }

        $navigationRequestBuilder->setRevision('2.5.10');
        $response = $navigationRequestBuilder->sendRequest();
        $this->assertEquals(0, $response->getResponseTime(), '', 0.1);
    }

    /**
     * @dataProvider queryProvider
     * @param string $query
     * @param string $expectedResult
     */
    public function testSetQueryCanBeSetAndIsFormattedAsQueryString($query, $expectedResult)
    {
        $expectedParameter = sprintf('&query=%s', $expectedResult);

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
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

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
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

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setShopkey($expectedShopkey);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidShopkeyProvider
     * @param mixed $invalidShopkey
     */
    public function testInvalidShopkeyWillThrowAnException($invalidShopkey)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter shopkey is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setShopkey($invalidShopkey);
    }

    public function testShopkeyIsAutomaticallyAddedFromTheConfigIfNotOverridden()
    {
        $expectedParameter = sprintf('&shopkey=%s', $this->config->getServiceId());

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
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

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setShopurl($shopurl);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidShopurlProvider
     * @param mixed $invalidShopurl
     */
    public function testInvalidShopurlWillThrowAnException($invalidShopurl)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter shopurl is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
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

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setUserip($userip);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidUseripProvider
     * @param mixed $invalidUserip
     */
    public function testInvalidUseripWillThrowAnException($invalidUserip)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter userip is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
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

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setReferer($referer);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidRefererProvider
     * @param mixed $invalidReferer
     */
    public function testInvalidRefererWillThrowAnException($invalidReferer)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter referer is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
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

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setRevision($revision);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidRevisionProvider
     * @param mixed $invalidRevision
     */
    public function testInvalidRevisionWillThrowAnException($invalidRevision)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter revision is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setRevision($invalidRevision);
    }

    /**
     * @dataProvider attributeProvider
     * @param string $expectedAttributeName
     * @param string $expectedAttributeValue
     * @param string $specifier
     */
    public function testAddAttributeWillSetItInAValidFormat(
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

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addAttribute($expectedAttributeName, $expectedAttributeValue, $specifier);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidAttributeProvider
     * @param mixed $expectedAttributeName
     * @param mixed $expectedAttributeValue
     * @param mixed $specifier
     */
    public function testInvalidAttributeWillThrowAnException(
        $expectedAttributeName,
        $expectedAttributeValue,
        $specifier
    ) {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter attrib is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
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

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setOrder($expectedOrder);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidOrderProvider
     * @param mixed $invalidOrder
     */
    public function testInvalidOrderWillThrowAnException($invalidOrder)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter order is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setOrder($invalidOrder);
    }

    /**
     * @dataProvider propertyProvider
     * @param string $expectedProperty
     */
    public function testAddPropertyWillSetItInAValidFormat($expectedProperty)
    {
        $expectedParameter = sprintf('&properties%%5B%%5D=%s', $expectedProperty);

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addProperty($expectedProperty);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidPropertyProvider
     * @param mixed $invalidProperty
     */
    public function testInvalidPropertyWillThrowAnException($invalidProperty)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter properties is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addProperty($invalidProperty);
    }

    /**
     * @dataProvider pushAttribProvider
     * @param string $expectedFilterName
     * @param string $expectedFilterValue
     * @param float $expectedFactor
     */
    public function testAddPushAttribWillSetItInAValidFormat(
        $expectedFilterName,
        $expectedFilterValue,
        $expectedFactor
    ) {
        $expectedParameter = sprintf(
        // Decoded this looks like &pushAttrib[%s][%s]=%s
            '&pushAttrib%%5B%s%%5D%%5B%s%%5D=%s',
            $expectedFilterName,
            $expectedFilterValue,
            $expectedFactor
        );

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addPushAttrib($expectedFilterName, $expectedFilterValue, $expectedFactor);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidPushAttribProvider
     * @param mixed $expectedFilterName
     * @param mixed $expectedFilterValue
     * @param mixed $expectedFactor
     */
    public function testInvalidPushAttribWillThrowAnException(
        $expectedFilterName,
        $expectedFilterValue,
        $expectedFactor
    ) {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter pushAttrib is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addPushAttrib($expectedFilterName, $expectedFilterValue, $expectedFactor);
    }

    /**
     * @dataProvider countProvider
     * @param int $expectedCount
     */
    public function testSetCountWillSetItInAValidFormat($expectedCount)
    {
        $expectedParameter = sprintf('&count=%s', $expectedCount);

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setCount($expectedCount);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidCountProvider
     * @param mixed $invalidCount
     */
    public function testInvalidCountWillThrowAnException($invalidCount)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter count is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setCount($invalidCount);
    }

    /**
     * @dataProvider firstProvider
     * @param int $expectedFirst
     */
    public function testSetFirstWillSetItInAValidFormat($expectedFirst)
    {
        $expectedParameter = sprintf('&first=%s', $expectedFirst);

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setFirst($expectedFirst);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidFirstProvider
     * @param mixed $invalidFirst
     */
    public function testInvalidFirstWillThrowAnException($invalidFirst)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter first is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setFirst($invalidFirst);
    }

    /**
     * @dataProvider identifierProvider
     * @param string $expectedIdentifier
     */
    public function testSetIdentifierWillSetItInAValidFormat($expectedIdentifier)
    {
        $expectedParameter = sprintf('&identifier=%s', $expectedIdentifier);

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setIdentifier($expectedIdentifier);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidIdentifierProvider
     * @param mixed $invalidIdentifier
     */
    public function testInvalidIdentifierWillThrowAnException($invalidIdentifier)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter identifier is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setIdentifier($invalidIdentifier);
    }

    /**
     * @dataProvider groupProvider
     * @param string $expectedGroup
     */
    public function testAddGroupWillSetItInAValidFormat($expectedGroup)
    {
        $expectedParameter = sprintf('&group%%5B%%5D=%s', $expectedGroup);

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addGroup($expectedGroup);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider invalidGroupProvider
     * @param mixed $invalidGroup
     */
    public function testInvalidGroupWillThrowAnException($invalidGroup)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter group is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addGroup($invalidGroup);
    }

    public function testSetForceOriginalQueryWillSetItInAValidFormat()
    {
        $expectedParameter = '&forceOriginalQuery=1';

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setForceOriginalQuery();
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    /**
     * @dataProvider individualParamProvider
     * @param string $expectedKey
     * @param string $expectedValue
     * @param string $expectedMethod
     */
    public function testAddIndividualParamWillSetItInAValidFormat($expectedKey, $expectedValue, $expectedMethod)
    {
        $encodedKey = urlencode($expectedKey);
        $encodedValue = urlencode($expectedValue);
        $expectedParameter = sprintf('&%s=%s', $encodedKey, $encodedValue);

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addIndividualParam($expectedKey, $expectedValue, $expectedMethod);
        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParameter, $requestUrl);
    }

    public function testAddingParamsWithARandomValueWillThrowAnException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown method type.');

        $expectedKey = 'someKey';
        $expectedValue = 'someValue';
        $expectedInvalidMethod = 'invalidMethod';

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addIndividualParam($expectedKey, $expectedValue, $expectedInvalidMethod);
    }

    public function testAddParamsOverTheSameKeyWillMergeThemTogether()
    {
        $expectedGroups = ['groupOne', 'groupTwo', 'muchMoreGroups'];
        $expectedParam = '';
        foreach ($expectedGroups as $group) {
            // We can not urlencode the entire string since the `&` and `=` would be encoded as well.
            $expectedParam .= '&' . urlencode('group[][]') . '=' . urlencode($group);
        }

        $searchRequestBuilder = new SearchRequestBuilder($this->config);
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder
            ->addGroup($expectedGroups[0])
            ->addGroup($expectedGroups[1])
            ->addGroup($expectedGroups[2]);

        $requestUrl = $searchRequestBuilder->buildRequestUrl();
        $this->assertContains($expectedParam, $requestUrl);
    }
}
