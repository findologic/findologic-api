<?php

namespace FINDOLOGIC_DEV\Tests\Helpers;

use FINDOLOGIC_DEV\Helpers\ParameterBuilder;
use PHPUnit\Framework\TestCase;

class ParameterBuilderTest extends TestCase
{
    /** @var $parameterBuilder ParameterBuilder */
    public $parameterBuilder;

    public function setUp()
    {
        $this->parameterBuilder = new ParameterBuilder();
    }

    /**
     * Returns some shopkeys that might be set by users. Invalid shopkeys are not tested since they should not even get
     * to this point.
     *
     * @return array
     */
    public function shopkeyProvider()
    {
        return [
            'normal shopkey' => ['ABCDEFABCDEFAB123456789123456789'],
            'other shopkey' => ['80AB18D4BE2654A78244106AD315DC2C'],
            'more different shopkey' => ['11111111111111112222222222222222']
        ];
    }

    /**
     * @dataProvider shopkeyProvider
     * @param $expectedShopkey string
     */
    public function testSetShopkeyWillSetItInAValidFormat($expectedShopkey)
    {
        $paramName = ParameterBuilder::SHOPKEY;

        $this->parameterBuilder->setShopkey($expectedShopkey);
        $shopkey = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedShopkey, $shopkey);
    }

    /**
     * Returns some shopurls that might be set by users. Invalid shopurls are not tested since they should not even get
     * to this point.
     *
     * @return array
     */
    public function shopurlProvider()
    {
        return [
            'normal shopurl' => ['www.poop.com'],
            'other shopurl' => ['www.shop.co.de'],
            'more different shopurl' => ['blubbergurken.de/shop']
        ];
    }

    /**
     * @dataProvider shopurlProvider
     * @param $expectedShopurl string
     */
    public function testSetShopurlWillSetItInAValidFormat($expectedShopurl)
    {
        $paramName = ParameterBuilder::SHOP_URL;

        $this->parameterBuilder->setShopurl($expectedShopurl);
        $shopurl = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedShopurl, $shopurl);
    }

    /**
     * Returns some userip that might be set by users. Invalid userips are not tested since they should not even get
     * to this point.
     *
     * @return array
     */
    public function useripProvider()
    {
        return [
            'normal userip' => ['127.0.0.1'],
            'other userip' => ['183.12.42.33'],
            'more different userip' => ['255.255.255.255']
        ];
    }

    /**
     * @dataProvider useripProvider
     * @param $expectedUserip string
     */
    public function testSetUseripWillSetItInAValidFormat($expectedUserip)
    {
        $paramName = ParameterBuilder::USER_IP;

        $this->parameterBuilder->setUserip($expectedUserip);
        $userip = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedUserip, $userip);
    }

    /**
     * Returns some referer that might be set by users. Invalid referer are not tested since they should not even get
     * to this point.
     *
     * @return array
     */
    public function refererProvider()
    {
        return [
            'normal referer' => ['https://www.somedomain.de/best-category'],
            'other referer' => ['http://this-is-an-unsecure-domain.com/unsecure'],
            'more different referer' => ['http://www.domain.ru/domains']
        ];
    }

    /**
     * @dataProvider refererProvider
     * @param $expectedReferer string
     */
    public function testSetRefererWillSetItInAValidFormat($expectedReferer)
    {
        $paramName = ParameterBuilder::REFERER;

        $this->parameterBuilder->setReferer($expectedReferer);
        $referer = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedReferer, $referer);
    }

    /**
     * Returns some revision that might be set by users. Invalid revisions are not tested since they should not even get
     * to this point.
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
     * @param $expectedRevision string
     */
    public function testSetRevisionWillSetItInAValidFormat($expectedRevision)
    {
        $paramName = ParameterBuilder::REVISION;

        $this->parameterBuilder->setRevision($expectedRevision);
        $revision = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedRevision, $revision);
    }

    /**
     * Returns some query that might be set by users. Invalid queries are not tested since they should not even get
     * to this point.
     *
     * @return array
     */
    public function queryProvider()
    {
        return [
            'normal query' => ['shoes'],
            'other query' => ['really good shoes'],
            'more different query' => ['best & *_\' shoes &copy; ever!']
        ];
    }

    /**
     * @dataProvider queryProvider
     * @param $expectedQuery string
     */
    public function testSetQueryWillSetItInAValidFormat($expectedQuery)
    {
        $paramName = ParameterBuilder::QUERY;

        $this->parameterBuilder->setQuery($expectedQuery);
        $query = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * Returns some attribute that might be set by users. Invalid attributes are not tested since they should not even
     * get to this point.
     *
     * @return array
     */
    public function attributeProvider()
    {
        return [
            'normal attribute' => ['vendor', 'Tom Tailor', null],
            'other attribute' => ['Material', 'Leather', null],
            'more different attribute' => ['price', '200.0', ParameterBuilder::SLIDER_MAX]
        ];
    }

    /**
     * @dataProvider attributeProvider
     * @param $expectedAttributeName string
     * @param $expectedAttributeValue string
     * @param $specifier string
     */
    public function testSetAttributeWillSetItInAValidFormat(
        $expectedAttributeName,
        $expectedAttributeValue,
        $specifier
    ) {
        $expectedAttribute = [$expectedAttributeName => [$specifier => $expectedAttributeValue]];
        $paramName = ParameterBuilder::ATTRIB;

        $this->parameterBuilder->addAttribute($expectedAttributeName, $expectedAttributeValue, $specifier);
        $attribute = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedAttribute, $attribute);
    }

    /**
     * Returns some order that might be set by users. Invalid orders are not tested since they should not even get
     * to this point.
     *
     * @return array
     */
    public function orderProvider()
    {
        return [
            'normal order' => ['shoes'],
            'other order' => ['really good shoes'],
            'more different order' => ['best & *_\' shoes &copy; ever!']
        ];
    }

    /**
     * @dataProvider orderProvider
     * @param $expectedOrder string
     */
    public function testSetOrderWillSetItInAValidFormat($expectedOrder)
    {
        $paramName = ParameterBuilder::ORDER;

        $this->parameterBuilder->setOrder($expectedOrder);
        $order = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedOrder, $order);
    }

    /**
     * Returns some property that might be set by users. Invalid properties are not tested since they should not even
     * get to this point.
     *
     * @return array
     */
    public function propertyProvider()
    {
        return [
            'normal property' => ['ordernumber'],
            'other property' => ['detailedDescription'],
            'more different property' => ['articleName']
        ];
    }

    /**
     * @dataProvider propertyProvider
     * @param $expectedPropertyValue string
     */
    public function testSetPropertyWillSetItInAValidFormat($expectedPropertyValue)
    {
        $paramName = ParameterBuilder::PROPERTIES;
        $expectedProperty = ['' => $expectedPropertyValue];

        $this->parameterBuilder->addProperty($expectedPropertyValue);
        $property = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedProperty, $property);
    }

    /**
     * Returns some pushAttrib that might be set by users. Invalid pushAttribs are not tested since they should not even
     * get to this point.
     *
     * @return array
     */
    public function pushAttribProvider()
    {
        return [
            'normal pushAttrib' => ['vendor', 'Tom Tailor', 0.3],
            'other pushAttrib' => ['Material', 'Leather', 2.1],
            'more different pushAttrib' => ['Color', 'Black', 3]
        ];
    }

    /**
     * @dataProvider pushAttribProvider
     * @param $expectedPushAttribName string
     * @param $expectedPushAttribValue string
     * @param $factor int|float
     */
    public function testSetPushAttribWillSetItInAValidFormat($expectedPushAttribName, $expectedPushAttribValue, $factor)
    {
        $expectedAttribute = [$expectedPushAttribName => [$factor => $expectedPushAttribValue]];
        $paramName = ParameterBuilder::ATTRIB;

        $this->parameterBuilder->addAttribute($expectedPushAttribName, $expectedPushAttribValue, $factor);
        $attribute = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedAttribute, $attribute);
    }

    /**
     * Returns some count that might be set by users. Invalid counts are not tested since they should not even
     * get to this point.
     *
     * @return array
     */
    public function countProvider()
    {
        return [
            'normal count' => [20],
            'other count' => [25],
            'more different count' => [256]
        ];
    }

    /**
     * @dataProvider countProvider
     * @param $expectedCount string
     */
    public function testSetCountWillSetItInAValidFormat($expectedCount)
    {
        $paramName = ParameterBuilder::COUNT;

        $this->parameterBuilder->setCount($expectedCount);
        $count = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedCount, $count);
    }

    /**
     * Returns some first params that might be set by users. Invalid first params are not tested since they should not
     * even get to this point.
     *
     * @return array
     */
    public function firstProvider()
    {
        return [
            'normal first' => [20],
            'other first' => [25],
            'more different first' => [256]
        ];
    }

    /**
     * @dataProvider firstProvider
     * @param $expectedFirst string
     */
    public function testSetFirstWillSetItInAValidFormat($expectedFirst)
    {
        $paramName = ParameterBuilder::FIRST;

        $this->parameterBuilder->setFirst($expectedFirst);
        $first = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedFirst, $first);
    }

    /**
     * Returns some identifier that might be set by users. Invalid identifiers are not tested since they should not
     * even get to this point.
     *
     * @return array
     */
    public function identifierProvider()
    {
        return [
            'normal identifier' => ['15356'],
            'other identifier' => ['a23fv'],
            'more different identifier' => ['019111105-37900']
        ];
    }

    /**
     * @dataProvider firstProvider
     * @param $expectedFirst string
     */
    public function testSetIdentifierWillSetItInAValidFormat($expectedFirst)
    {
        $paramName = ParameterBuilder::FIRST;

        $this->parameterBuilder->setFirst($expectedFirst);
        $first = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedFirst, $first);
    }
}
