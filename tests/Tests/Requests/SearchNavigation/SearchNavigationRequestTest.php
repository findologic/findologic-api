<?php

namespace FINDOLOGIC\Api\Tests\Requests\SearchNavigation;

use FINDOLOGIC\Api\Client;
use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\Requests\SearchNavigation\NavigationRequest;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchRequest;
use FINDOLOGIC\Api\Responses\Xml21\Xml21Response;
use FINDOLOGIC\Api\Tests\TestBase;
use InvalidArgumentException;

/**
 * This class is testing the request for search and navigation. Most tests are using the
 * SearchRequest since navigation is basically the same except that query is not a required param for
 * navigation requests.
 */
class SearchNavigationRequestTest extends TestBase
{
    use SearchNavigationRequestDataProvider;

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

        $this->rawMockResponse = $this->getMockResponse('Xml21/demoResponse.xml');
    }

    public function requiredParamsProvider()
    {
        return [
            'config' => [[
                'shopurl' => 'blubbergurken.io',
                'userip' => '127.0.0.1',
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
        $this->httpClientMock->method('get')->willReturn($this->responseMock);
        $this->responseMock->method('getBody')->willReturn($this->streamMock);
        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->streamMock->method('getContents')
            ->willReturnOnConsecutiveCalls(
                'alive',
                $this->rawMockResponse
            );

        $searchRequest = new SearchRequest();
        $client = new Client($this->config);

        foreach ($options as $key => $value) {
            try {
                $client->send($searchRequest);
                $this->fail("An exception was expected to happen if the $key param is not set.");
            } catch (ParamNotSetException $e) {
                $this->assertEquals("Required param $key is not set.", $e->getMessage());
            }

            $setter = "set$key";
            $searchRequest->$setter($value);
        }

        $searchRequest->setQuery('');

        /** @var Xml21Response $response */
        $response = $client->send($searchRequest);
        $this->assertEquals(0, $response->getResponseTime(), '', 0.1);
    }

    public function testSendingNavigationRequestsWithoutRequiredParamsWillThrowAnException()
    {
        $this->httpClientMock->method('get')->willReturn($this->responseMock);
        $this->responseMock->method('getBody')->willReturn($this->streamMock);
        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->streamMock->method('getContents')
            ->willReturnOnConsecutiveCalls(
                'alive',
                $this->rawMockResponse
            );

        $navigationRequest = new NavigationRequest();
        $client = new Client($this->config);
        try {
            $client->send($navigationRequest);
            $this->fail('An exception was expected to happen if the shopurl param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param shopurl is not set.', $e->getMessage());
        }

        $navigationRequest->setShopUrl('blubbergurken.io');
        try {
            $client->send($navigationRequest);
            $this->fail('An exception was expected to happen if the userip param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param userip is not set.', $e->getMessage());
        }

        $navigationRequest->setUserIp('127.0.0.1');
        try {
            $client->send($navigationRequest);
            $this->fail('An exception was expected to happen if the revision param is not set.');
        } catch (ParamNotSetException $e) {
            $this->assertEquals('Required param revision is not set.', $e->getMessage());
        }

        $navigationRequest->setRevision('2.5.10');

        /** @var Xml21Response $response */
        $response = $client->send($navigationRequest);
        $this->assertEquals(0, $response->getResponseTime(), '', 0.1);
    }

    /**
     * @dataProvider queryProvider
     * @param string $expectedQuery
     */
    public function testSetQueryCanBeSetAndIsFormattedAsQueryString($expectedQuery)
    {
        $expectedParameter = 'query';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setQuery($expectedQuery);
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setQuery($invalidQuery);
    }

    /**
     * @dataProvider shopkeyProvider
     * @param string $expectedShopkey
     */
    public function testSetShopkeyCanBeSetAndIsFormattedAsQueryString($expectedShopkey)
    {
        $expectedParameter = 'shopkey';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setShopkey($expectedShopkey);
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setShopkey($invalidShopkey);
    }

    public function testShopkeyIsAutomaticallyAddedFromTheConfigIfNotOverridden()
    {
        $expectedParameter = 'shopkey';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setShopkey($this->config->getServiceId());
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setShopUrl($expectedShopurl);
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setShopUrl($invalidShopurl);
    }

    /**
     * @dataProvider useripProvider
     * @param string $expectedUserip
     */
    public function testUseripCanBeSetAndIsFormattedAsQueryString($expectedUserip)
    {
        $expectedParameter = 'userip';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setUserIp($expectedUserip);
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setUserIp($invalidUserip);
    }

    /**
     * @dataProvider refererProvider
     * @param string $expectedReferer
     */
    public function testRefererCanBeSetAndIsFormattedAsQueryString($expectedReferer)
    {
        $expectedParameter = 'referer';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setReferer($expectedReferer);
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setReferer($invalidReferer);
    }

    /**
     * @dataProvider revisionProvider
     * @param string $expectedRevision
     */
    public function testRevisionCanBeSetAndIsFormattedAsQueryString($expectedRevision)
    {
        $expectedParameter = 'revision';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setRevision($expectedRevision);
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setRevision($invalidRevision);
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addAttribute($expectedAttributeName, $expectedAttributeValue, $specifier);
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addAttribute($expectedAttributeName, $expectedAttributeValue, $specifier);
    }

    /**
     * @dataProvider orderProvider
     * @param string $expectedOrder
     */
    public function testSetOrderWillSetItInAValidFormat($expectedOrder)
    {
        $expectedParameter = 'order';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setOrder($expectedOrder);
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setOrder($invalidOrder);
    }

    /**
     * @dataProvider propertyProvider
     * @param string $expectedProperty
     */
    public function testAddPropertyWillSetItInAValidFormat($expectedProperty)
    {
        $expectedParameter = 'properties';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addProperty($expectedProperty);
        $params = $searchRequest->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals([$expectedProperty], $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidPropertyProvider
     * @param mixed $invalidProperty
     */
    public function testInvalidPropertyWillThrowAnException($invalidProperty)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter properties is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addProperty($invalidProperty);
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addPushAttrib($expectedFilterName, $expectedFilterValue, $expectedFactor);
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addPushAttrib($expectedFilterName, $expectedFilterValue, $expectedFactor);
    }

    /**
     * @dataProvider countProvider
     * @param int $expectedCount
     */
    public function testSetCountWillSetItInAValidFormat($expectedCount)
    {
        $expectedParameter = 'count';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setCount($expectedCount);
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setCount($invalidCount);
    }

    /**
     * @dataProvider firstProvider
     * @param int $expectedFirst
     */
    public function testSetFirstWillSetItInAValidFormat($expectedFirst)
    {
        $expectedParameter = 'first';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setFirst($expectedFirst);
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setFirst($invalidFirst);
    }

    /**
     * @dataProvider identifierProvider
     * @param string $expectedIdentifier
     */
    public function testSetIdentifierWillSetItInAValidFormat($expectedIdentifier)
    {
        $expectedParameter = 'identifier';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setIdentifier($expectedIdentifier);
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setIdentifier($invalidIdentifier);
    }

    /**
     * @dataProvider outputAttribProvider
     * @param string $expectedOutputAttrib
     */
    public function testSetOutputAttribWillSetItInAValidFormat($expectedOutputAttrib)
    {
        $expectedParameter = 'outputAttrib';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addOutputAttrib($expectedOutputAttrib);
        $params = $searchRequest->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals([$expectedOutputAttrib], $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidOutputAttribProvider
     * @param mixed $outputAttrib
     */
    public function testInvalidOutputAttribWillThrowAnException($outputAttrib)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter outputAttrib is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addOutputAttrib($outputAttrib);
    }

    /**
     * @dataProvider groupProvider
     * @param string $expectedGroup
     */
    public function testAddGroupWillSetItInAValidFormat($expectedGroup)
    {
        $expectedParameter = 'group';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addGroup($expectedGroup);
        $params = $searchRequest->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals([$expectedGroup], $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidGroupProvider
     * @param mixed $invalidGroup
     */
    public function testInvalidGroupWillThrowAnException($invalidGroup)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter group is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addGroup($invalidGroup);
    }

    /**
     * @dataProvider userGroupProvider
     *
     * @param string $expectedUserGroupHash
     */
    public function testAddUserGroupWillSetItInAValidFormat($expectedUserGroupHash)
    {
        $expectedParameter = 'usergrouphash';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addUserGroup($expectedUserGroupHash);
        $params = $searchRequest->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals([$expectedUserGroupHash], $params[$expectedParameter]);
    }

    /**
     * @dataProvider invalidUserGroupProvider
     * @param mixed $invalidUserGroup
     */
    public function testInvalidUserGroupWillThrowAnException($invalidUserGroup)
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter usergrouphash is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addUserGroup($invalidUserGroup);
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

        $navigationRequest = new NavigationRequest();
        $this->setRequiredParamsForSearchNavigationRequest($navigationRequest);

        $navigationRequest->setSelected($expectedAttributeName, $expectedAttributeValue);
        $params = $navigationRequest->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals(
            [$expectedAttributeName => [$expectedAttributeValue]],
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

        $navigationRequest = new NavigationRequest();
        $this->setRequiredParamsForSearchNavigationRequest($navigationRequest);

        $navigationRequest->setSelected($expectedAttributeName, $expectedAttributeValue);
    }

    public function testSetForceOriginalQueryWillSetItInAValidFormat()
    {
        $expectedForceOriginalQuery = 1;
        $expectedParameter = 'forceOriginalQuery';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setForceOriginalQuery();
        $params = $searchRequest->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedForceOriginalQuery, $params[$expectedParameter]);
    }

    public function testSetOutputAdapterWillSetItInAValidFormat()
    {
        $expectedOutputAdapter = 'XML_2.1';
        $expectedParameter = 'outputAdapter';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setOutputAdapter($expectedOutputAdapter);
        $params = $searchRequest->getParams();
        $this->assertArrayHasKey($expectedParameter, $params);
        $this->assertEquals($expectedOutputAdapter, $params[$expectedParameter]);
    }

    public function testInvalidOutputAdapterWillThrowAnException()
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter outputAdapter is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setOutputAdapter('HTML_51');
    }

    /**
     * @dataProvider individualParamProvider
     * @param string $expectedKey
     * @param string $expectedValue
     * @param string $expectedMethod
     */
    public function testAddIndividualParamWillSetItInAValidFormat($expectedKey, $expectedValue, $expectedMethod)
    {
        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addIndividualParam($expectedKey, $expectedValue, $expectedMethod);
        $params = $searchRequest->getParams();
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

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addIndividualParam($expectedKey, $expectedValue, $expectedInvalidMethod);
    }

    public function testAddParamsOverTheSameKeyWillMergeThemTogether()
    {
        $expectedGroups = ['groupOne', 'groupTwo', 'muchMoreGroups'];
        $expectedParam = 'group';

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest
            ->addGroup($expectedGroups[0])
            ->addGroup($expectedGroups[1])
            ->addGroup($expectedGroups[2]);

        $params = $searchRequest->getParams();
        $this->assertArrayHasKey($expectedParam, $params);
        $this->assertEquals($expectedGroups, $params[$expectedParam]);
    }

    public function testAttributesAreMergedTogetherProperly()
    {
        $searchRequest = new SearchRequest();

        $searchRequest
            ->setShopUrl('blubbergurken.io')
            ->addAttribute('someFilter', 'someValue')
            ->addAttribute('someFilter', 'someOtherValue');

        $expectedUri = 'https://service.findologic.com/ps/blubbergurken.io/index.php?shopurl=blubbergurken.io&';
        $expectedUri .= 'attrib%5BsomeFilter%5D%5B0%5D=someValue&attrib%5BsomeFilter%5D%5B1%5D=someOtherValue';
        $expectedUri .= '&shopkey=ABCDABCDABCDABCDABCDABCDABCDABCD';

        $uri = $searchRequest->buildRequestUrl($this->config);
        $this->assertEquals($expectedUri, $uri);
    }

    public function testAttributesAreNotMergedWhenNotNeeded()
    {
        $searchRequest = new SearchRequest();

        $searchRequest
            ->setShopUrl('blubbergurken.io')
            ->addAttribute('someFilter1', 'someValue')
            ->addAttribute('someFilter2', 'someOtherValue');

        $expectedUri = 'https://service.findologic.com/ps/blubbergurken.io/index.php?shopurl=blubbergurken.io&';
        $expectedUri .= 'attrib%5BsomeFilter1%5D%5B%5D=someValue&attrib%5BsomeFilter2%5D%5B%5D=someOtherValue';
        $expectedUri .= '&shopkey=ABCDABCDABCDABCDABCDABCDABCDABCD';

        $uri = $searchRequest->buildRequestUrl($this->config);
        $this->assertEquals($expectedUri, $uri);
    }
}
