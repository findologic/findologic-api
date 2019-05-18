<?php

namespace FINDOLOGIC\Api\Tests\RequestBuilders\Xml;

use FINDOLOGIC\Api\Client;
use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\RequestBuilders\Xml20\NavigationRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml20\SearchRequestBuilder;
use FINDOLOGIC\Api\ResponseObjects\Xml20\Xml20Response;
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

    public function requiredParamsProvider()
    {
        return [
            'config' => [[
                'shopurl' => 'blubbergurken.io',
                'userip' => '127.0.0.1',
                'referer' => 'https://blubbergurken.io/blubbergurken-sale/',
                'revision' => '1.0.0',
            ]]
        ];
    }

    /**
     * @dataProvider requiredParamsProvider
     * @param array $options
     */
    public function testSendingSearchRequestsWithoutRequiredParamsWillThrowAnException(array $options)
    {
        $this->httpClientMock->method('request')->willReturn($this->responseMock);
        $this->responseMock->method('getBody')->willReturn($this->streamMock);
        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->streamMock->method('getContents')
            ->willReturnOnConsecutiveCalls(
                'alive',
                $this->rawMockResponse
            );

        $searchRequestBuilder = new SearchRequestBuilder();
        $client = new Client($this->config);

        foreach ($options as $key => $value) {
            try {
                $client->send($searchRequestBuilder);
                $this->fail("An exception was expected to happen if the $key param is not set.");
            } catch (ParamNotSetException $e) {
                $this->assertEquals("Required param $key is not set.", $e->getMessage());
            }

            $setter = "set$key";
            $searchRequestBuilder->$setter($value);
        }

        $searchRequestBuilder->setQuery('');

        /** @var Xml20Response $response */
        $response = $client->send($searchRequestBuilder);
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
                $this->rawMockResponse
            );

        $navigationRequestBuilder = new NavigationRequestBuilder();
        $client = new Client($this->config);
        try {
            $client->send($navigationRequestBuilder);
            $this->fail('An exception was expected to happen if the shopurl param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param shopurl is not set.', $e->getMessage());
        }

        $navigationRequestBuilder->setShopUrl('blubbergurken.io');
        try {
            $client->send($navigationRequestBuilder);
            $this->fail('An exception was expected to happen if the userip param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param userip is not set.', $e->getMessage());
        }

        $navigationRequestBuilder->setUserIp('127.0.0.1');
        try {
            $client->send($navigationRequestBuilder);
            $this->fail('An exception was expected to happen if the referer param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param referer is not set.', $e->getMessage());
        }

        $navigationRequestBuilder->setReferer('https://blubbergurken.io/blubbergurken-sale/');
        try {
            $client->send($navigationRequestBuilder);
            $this->fail('An exception was expected to happen if the revision param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param revision is not set.', $e->getMessage());
        }

        $navigationRequestBuilder->setRevision('2.5.10');

        /** @var Xml20Response $response */
        $response = $client->send($navigationRequestBuilder);
        $this->assertEquals(0, $response->getResponseTime(), '', 0.1);
    }

    /**
     * @dataProvider queryProvider
     * @param string $expectedQuery
     */
    public function testSetQueryCanBeSetAndIsFormattedAsQueryString($expectedQuery)
    {
        $expectedParameter = 'query';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setQuery($expectedQuery);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedQuery, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidQueryProvider
     * @param mixed $invalidQuery
     */
    public function testSetQueryWillThrowAnExceptionWhenSubmittingInvalidQueries($invalidQuery)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter query is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setQuery($invalidQuery);
    }

    /**
     * @dataProvider shopkeyProvider
     * @param string $expectedShopkey
     */
    public function testSetShopkeyCanBeSetAndIsFormattedAsQueryString($expectedShopkey)
    {
        $expectedParameter = 'shopkey';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setShopkey($expectedShopkey);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedShopkey, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidShopkeyProvider
     * @param mixed $invalidShopkey
     */
    public function testInvalidShopkeyWillThrowAnException($invalidShopkey)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter shopkey is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setShopkey($invalidShopkey);
    }

    public function testShopkeyIsAutomaticallyAddedFromTheConfigIfNotOverridden()
    {
        $expectedParameter = 'shopkey';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setShopkey($this->config->getServiceId());
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($this->config->getServiceId(), $params[$expectedParameter]);
    }

    /**
     * @dataProvider shopurlProvider
     * @param string $expectedShopurl
     */
    public function testShopurlCanBeSetAndIsFormattedAsQueryString($expectedShopurl)
    {
        $expectedParameter = 'shopurl';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setShopUrl($expectedShopurl);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedShopurl, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidShopurlProvider
     * @param mixed $invalidShopurl
     */
    public function testInvalidShopurlWillThrowAnException($invalidShopurl)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter shopurl is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setShopUrl($invalidShopurl);
    }

    /**
     * @dataProvider useripProvider
     * @param string $expectedUserip
     */
    public function testUseripCanBeSetAndIsFormattedAsQueryString($expectedUserip)
    {
        $expectedParameter = 'userip';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setUserIp($expectedUserip);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedUserip, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidUseripProvider
     * @param mixed $invalidUserip
     */
    public function testInvalidUseripWillThrowAnException($invalidUserip)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter userip is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setUserIp($invalidUserip);
    }

    /**
     * @dataProvider refererProvider
     * @param string $expectedReferer
     */
    public function testRefererCanBeSetAndIsFormattedAsQueryString($expectedReferer)
    {
        $expectedParameter = 'referer';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setReferer($expectedReferer);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedReferer, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidRefererProvider
     * @param mixed $invalidReferer
     */
    public function testInvalidRefererWillThrowAnException($invalidReferer)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter referer is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setReferer($invalidReferer);
    }

    /**
     * @dataProvider revisionProvider
     * @param string $expectedRevision
     */
    public function testRevisionCanBeSetAndIsFormattedAsQueryString($expectedRevision)
    {
        $expectedParameter = 'revision';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setRevision($expectedRevision);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedRevision, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidRevisionProvider
     * @param mixed $invalidRevision
     */
    public function testInvalidRevisionWillThrowAnException($invalidRevision)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter revision is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder();
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
        $expectedParameter = 'attrib';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addAttribute($expectedAttributeName, $expectedAttributeValue, $specifier);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals(
            [$expectedAttributeName => [$specifier => $expectedAttributeValue]],
            $params[$expectedParameter]
        );
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

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addAttribute($expectedAttributeName, $expectedAttributeValue, $specifier);
    }

    /**
     * @dataProvider orderProvider
     * @param string $expectedOrder
     */
    public function testSetOrderWillSetItInAValidFormat($expectedOrder)
    {
        $expectedParameter = 'order';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setOrder($expectedOrder);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedOrder, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidOrderProvider
     * @param mixed $invalidOrder
     */
    public function testInvalidOrderWillThrowAnException($invalidOrder)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter order is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setOrder($invalidOrder);
    }

    /**
     * @dataProvider propertyProvider
     * @param string $expectedProperty
     */
    public function testAddPropertyWillSetItInAValidFormat($expectedProperty)
    {
        $expectedParameter = 'properties';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addProperty($expectedProperty);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals(['' => $expectedProperty], $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidPropertyProvider
     * @param mixed $invalidProperty
     */
    public function testInvalidPropertyWillThrowAnException($invalidProperty)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter properties is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder();
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
        $expectedParameter = 'pushAttrib';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addPushAttrib($expectedFilterName, $expectedFilterValue, $expectedFactor);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals(
            [$expectedFilterName => [$expectedFilterValue => $expectedFactor]],
            $params[$expectedParameter]
        );
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

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addPushAttrib($expectedFilterName, $expectedFilterValue, $expectedFactor);
    }

    /**
     * @dataProvider countProvider
     * @param int $expectedCount
     */
    public function testSetCountWillSetItInAValidFormat($expectedCount)
    {
        $expectedParameter = 'count';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setCount($expectedCount);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedCount, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidCountProvider
     * @param mixed $invalidCount
     */
    public function testInvalidCountWillThrowAnException($invalidCount)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter count is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setCount($invalidCount);
    }

    /**
     * @dataProvider firstProvider
     * @param int $expectedFirst
     */
    public function testSetFirstWillSetItInAValidFormat($expectedFirst)
    {
        $expectedParameter = 'first';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setFirst($expectedFirst);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedFirst, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidFirstProvider
     * @param mixed $invalidFirst
     */
    public function testInvalidFirstWillThrowAnException($invalidFirst)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter first is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setFirst($invalidFirst);
    }

    /**
     * @dataProvider identifierProvider
     * @param string $expectedIdentifier
     */
    public function testSetIdentifierWillSetItInAValidFormat($expectedIdentifier)
    {
        $expectedParameter = 'identifier';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setIdentifier($expectedIdentifier);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedIdentifier, $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidIdentifierProvider
     * @param mixed $invalidIdentifier
     */
    public function testInvalidIdentifierWillThrowAnException($invalidIdentifier)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter identifier is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setIdentifier($invalidIdentifier);
    }

    /**
     * @dataProvider outputAttribProvider
     * @param string $expectedOutputAttrib
     */
    public function testSetOutputAttribWillSetItInAValidFormat($expectedOutputAttrib)
    {
        $expectedParameter = 'outputAttrib';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addOutputAttrib($expectedOutputAttrib);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals(['' => $expectedOutputAttrib], $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidOutputAttribProvider
     * @param mixed $outputAttrib
     */
    public function testInvalidOutputAttribWillThrowAnException($outputAttrib)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter outputAttrib is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addOutputAttrib($outputAttrib);
    }

    /**
     * @dataProvider groupProvider
     * @param string $expectedGroup
     */
    public function testAddGroupWillSetItInAValidFormat($expectedGroup)
    {
        $expectedParameter = 'group';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addGroup($expectedGroup);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals(['' => $expectedGroup], $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidGroupProvider
     * @param mixed $invalidGroup
     */
    public function testInvalidGroupWillThrowAnException($invalidGroup)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter group is not valid.');

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addGroup($invalidGroup);
    }

    /**
     * @dataProvider attributeProvider
     * @param string $expectedAttributeName
     * @param string $expectedAttributeValue
     */
    public function testSetSelectedWillSetItInAValidFormat(
        $expectedAttributeName,
        $expectedAttributeValue
    ) {
        $expectedParameter = 'selected';

        $searchRequestBuilder = new NavigationRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setSelected($expectedAttributeName, $expectedAttributeValue);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals(
            [$expectedAttributeName => ['' => $expectedAttributeValue]],
            $params[$expectedParameter]
        );
    }

    /**
     * @dataProvider invalidSelectedProvider
     * @param mixed $expectedAttributeName
     * @param mixed $expectedAttributeValue
     */
    public function testInvalidSelectedWillThrowAnException(
        $expectedAttributeName,
        $expectedAttributeValue
    ) {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter selected is not valid.');

        $searchRequestBuilder = new NavigationRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setSelected($expectedAttributeName, $expectedAttributeValue);
    }

    public function testSetForceOriginalQueryWillSetItInAValidFormat()
    {
        $expectedForceOriginalQuery = 1;
        $expectedParameter = 'forceOriginalQuery';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->setForceOriginalQuery();
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedForceOriginalQuery, $params[$expectedParameter]);
    }

    /**
     * @dataProvider individualParamProvider
     * @param string $expectedKey
     * @param string $expectedValue
     * @param string $expectedMethod
     */
    public function testAddIndividualParamWillSetItInAValidFormat($expectedKey, $expectedValue, $expectedMethod)
    {
        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addIndividualParam($expectedKey, $expectedValue, $expectedMethod);
        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedKey, $params);
        $this->assertEquals($expectedValue, $params[$expectedKey]);
    }

    public function testAddingParamsWithARandomValueWillThrowAnException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown method type.');

        $expectedKey = 'someKey';
        $expectedValue = 'someValue';
        $expectedInvalidMethod = 'invalidMethod';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder->addIndividualParam($expectedKey, $expectedValue, $expectedInvalidMethod);
    }

    public function testAddParamsOverTheSameKeyWillMergeThemTogether()
    {
        $expectedGroups = ['groupOne', 'groupTwo', 'muchMoreGroups'];
        $expectedParam = 'group';

        $searchRequestBuilder = new SearchRequestBuilder();
        $this->setRequiredParamsForXmlRequestBuilder($searchRequestBuilder);

        $searchRequestBuilder
            ->addGroup($expectedGroups[0])
            ->addGroup($expectedGroups[1])
            ->addGroup($expectedGroups[2]);

        $params = $searchRequestBuilder->getParams();
        $this->assertArrayHasKey($expectedParam, $params);
        $this->assertContains($expectedGroups, $params[$expectedParam]);
    }
}
