<?php

namespace FINDOLOGIC\Api\Tests\RequestBuilders\Xml;

use FINDOLOGIC\Api\FindologicConfig;
use FINDOLOGIC\Api\RequestBuilders\Xml\NavigationRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\SearchRequestBuilder;
use FINDOLOGIC\Api\RequestBuilders\Xml\XmlRequestBuilder;
use FINDOLOGIC\Api\Tests\TestBase;

class XmlRequestBuilderTest extends TestBase
{
    /** @var FindologicConfig */
    private $findologicConfig;

    protected function setUp()
    {
        parent::setUp();
        $this->findologicConfig = new FindologicConfig(['shopkey' => 'ABCDABCDABCDABCDABCDABCDABCDABCD']);
    }

    public function orderProvider()
    {
        return [
            'normal order' => [
                'order' => 'price DESC',
                'expectedResult' => 'price+DESC',
            ],
            'other order' => [
                'order' => 'rank',
                'expectedResult' => 'rank',
            ],
            'more different order' => [
                'order' => 'dateadded DESC',
                'expectedResult' => 'dateadded+DESC',
            ],
        ];
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
}
