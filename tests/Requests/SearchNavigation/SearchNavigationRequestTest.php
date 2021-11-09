<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Requests\SearchNavigation;

use BadMethodCallException;
use FINDOLOGIC\Api\Client;
use FINDOLOGIC\Api\Config;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Exceptions\ParamNotSetException;
use FINDOLOGIC\Api\Requests\SearchNavigation\NavigationRequest;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchNavigationRequest;
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new Config();
        $this->config
            ->setServiceId('ABCDABCDABCDABCDABCDABCDABCDABCD')
            ->setHttpClient($this->httpClientMock);

        $this->rawMockResponse = $this->getMockResponse('Xml21/demoResponse.xml');
    }

    /**
     * @return array<string, array<int, array<string, string>>>
     */
    public function requiredParamsProvider(): array
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
     * @param array<string, string|int|float|bool|null> $options
     */
    public function testSendingSearchRequestsWithoutRequiredParamsWillThrowAnException(array $options): void
    {
        $this->httpClientMock->method('request')->willReturn($this->responseMock);
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
        $this->assertEqualsWithDelta(0, $response->getResponseTime(), 0.1);
    }

    public function testSendingNavigationRequestsWithoutRequiredParamsWillThrowAnException(): void
    {
        $this->httpClientMock->method('request')->willReturn($this->responseMock);
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
        $this->assertEqualsWithDelta(0, $response->getResponseTime(), 0.1);
    }

    /**
     * @dataProvider queryProvider
     * @param string $expectedQuery
     */
    public function testSetQueryCanBeSetAndIsFormattedAsQueryString($expectedQuery): void
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
     * @dataProvider shopkeyProvider
     * @param string $expectedShopkey
     */
    public function testSetShopkeyCanBeSetAndIsFormattedAsQueryString($expectedShopkey): void
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
     */
    public function testInvalidShopkeyWillThrowAnException(string $invalidShopkey): void
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter shopkey is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setShopkey($invalidShopkey);
    }

    public function testShopkeyIsAutomaticallyAddedFromTheConfigIfNotOverridden(): void
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
    public function testShopurlCanBeSetAndIsFormattedAsQueryString(string $expectedShopurl): void
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
     */
    public function testInvalidShopurlWillThrowAnException(string $invalidShopurl): void
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter shopurl is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setShopUrl($invalidShopurl);
    }

    /**
     * @dataProvider useripProvider
     */
    public function testUseripCanBeSetAndIsFormattedAsQueryString(string $expectedUserip): void
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
     */
    public function testInvalidUseripWillThrowAnException(string $invalidUserip): void
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter userip is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setUserIp($invalidUserip);
    }

    /**
     * @dataProvider refererProvider
     */
    public function testRefererCanBeSetAndIsFormattedAsQueryString(string $expectedReferer): void
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
     */
    public function testInvalidRefererWillThrowAnException(string $invalidReferer): void
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter referer is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setReferer($invalidReferer);
    }

    /**
     * @dataProvider revisionProvider
     */
    public function testRevisionCanBeSetAndIsFormattedAsQueryString(string $expectedRevision): void
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
     */
    public function testInvalidRevisionWillThrowAnException(string $invalidRevision): void
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter revision is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setRevision($invalidRevision);
    }

    /**
     * @dataProvider attributeProvider
     */
    public function testAddAttributeWillSetItInAValidFormat(
        string $expectedAttributeName,
        string $expectedAttributeValue,
        ?string $specifier
    ): void {
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
     * @param mixed $expectedAttributeValue
     */
    public function testInvalidAttributeWillThrowAnException(
        string $expectedAttributeName,
        $expectedAttributeValue,
        ?string $specifier
    ): void {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter attrib is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addAttribute($expectedAttributeName, $expectedAttributeValue, $specifier);
    }

    /**
     * @dataProvider orderProvider
     */
    public function testSetOrderWillSetItInAValidFormat(string $expectedOrder): void
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
     */
    public function testInvalidOrderWillThrowAnException(string $invalidOrder): void
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter order is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setOrder($invalidOrder);
    }

    /**
     * @dataProvider propertyProvider
     */
    public function testAddPropertyWillSetItInAValidFormat(string $expectedProperty): void
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
     * @dataProvider pushAttribProvider
     */
    public function testAddPushAttribWillSetItInAValidFormat(
        string $expectedFilterName,
        string $expectedFilterValue,
        float $expectedFactor
    ): void {
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
     * @param mixed $expectedFilterValue
     */
    public function testInvalidPushAttribWillThrowAnException(
        string $expectedFilterName,
        $expectedFilterValue,
        float $expectedFactor
    ): void {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter pushAttrib is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addPushAttrib($expectedFilterName, $expectedFilterValue, $expectedFactor);
    }

    /**
     * @dataProvider countProvider
     */
    public function testSetCountWillSetItInAValidFormat(int $expectedCount): void
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
     */
    public function testInvalidCountWillThrowAnException(int $invalidCount): void
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter count is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setCount($invalidCount);
    }

    /**
     * @dataProvider firstProvider
     */
    public function testSetFirstWillSetItInAValidFormat(int $expectedFirst): void
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
     */
    public function testInvalidFirstWillThrowAnException(int $invalidFirst): void
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter first is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setFirst($invalidFirst);
    }

    /**
     * @dataProvider identifierProvider
     */
    public function testSetIdentifierWillSetItInAValidFormat(string $expectedIdentifier): void
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
     * @dataProvider outputAttribProvider
     */
    public function testSetOutputAttribWillSetItInAValidFormat(string $expectedOutputAttrib): void
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
     * @dataProvider groupProvider
     */
    public function testAddGroupWillSetItInAValidFormat(string $expectedGroup): void
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
     * @dataProvider userGroupProvider
     */
    public function testAddUserGroupWillSetItInAValidFormat(string $expectedUserGroupHash): void
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
     * @dataProvider attributeProvider
     */
    public function testSetSelectedWillSetItInAValidFormat(
        string $expectedAttributeName,
        string $expectedAttributeValue
    ): void {
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

    public function testSetForceOriginalQueryWillSetItInAValidFormat(): void
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

    public function testSetOutputAdapterWillSetItInAValidFormat(): void
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

    public function testInvalidOutputAdapterWillThrowAnException(): void
    {
        $this->expectException(InvalidParamException::class);
        $this->expectExceptionMessage('Parameter outputAdapter is not valid.');

        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->setOutputAdapter('HTML_51');
    }

    /**
     * @dataProvider individualParamProvider
     */
    public function testAddIndividualParamWillSetItInAValidFormat(
        string $expectedKey,
        string $expectedValue,
        string $expectedMethod
    ): void {
        $searchRequest = new SearchRequest();
        $this->setRequiredParamsForSearchNavigationRequest($searchRequest);

        $searchRequest->addIndividualParam($expectedKey, $expectedValue, $expectedMethod);
        $params = $searchRequest->getParams();
        $this->assertArrayHasKey($expectedKey, $params);
        $this->assertEquals($expectedValue, $params[$expectedKey]);
    }

    public function testAddingParamsWithARandomValueWillThrowAnException(): void
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

    public function testAddParamsOverTheSameKeyWillMergeThemTogether(): void
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

    public function testAttributesAreMergedTogetherProperly(): void
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

    public function testAttributesAreNotMergedWhenNotNeeded(): void
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

    /**
     * @return array<string, array<string, NavigationRequest|SearchRequest>>
     */
    public function getBodyIsNotSupportedProvider(): array
    {
        return [
            'search' => [
                'request' => new SearchRequest()
            ],
            'navigation' => [
                'request' => new NavigationRequest()
            ],
        ];
    }

    /**
     * @dataProvider getBodyIsNotSupportedProvider
     */
    public function testGetBodyIsNotSupported(SearchNavigationRequest $request): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Request body is not supported for search & navigation requests');

        $request->getBody();
    }
}
