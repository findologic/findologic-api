<?php

namespace FINDOLOGIC\Tests\Helpers;

use FINDOLOGIC\Definitions\OrderType;
use FINDOLOGIC\Exceptions\InvalidParamException;
use FINDOLOGIC\Helpers\ParameterBuilder;
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
     * @param $invalidShopkey mixed
     */
    public function testExceptionIsThrownIfShopkeyIsInvalid($invalidShopkey)
    {
        try {
            $this->parameterBuilder->setShopkey($invalidShopkey);
            $this->fail('A InvalidParamException was expected to occur when the shopkey param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter shopkey is not valid.', $e->getMessage());
        }
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
     * @param $invalidShopurl mixed
     */
    public function testExceptionIsThrownIfShopurlIsInvalid($invalidShopurl)
    {
        try {
            $this->parameterBuilder->setShopurl($invalidShopurl);
            $this->fail('A InvalidParamException was expected to occur when the shopurl param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter shopurl is not valid.', $e->getMessage());
        }
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
     * @param $invalidShopurl mixed
     */
    public function testExceptionIsThrownIfUseriplIsInvalid($invalidShopurl)
    {
        try {
            $this->parameterBuilder->setUserip($invalidShopurl);
            $this->fail('A InvalidParamException was expected to occur when the shopurl param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter userip is not valid.', $e->getMessage());
        }
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
     * @dataProvider invalidShopurlProvider
     * @param $invalidReferer mixed
     */
    public function testExceptionIsThrownIfRefererlIsInvalid($invalidReferer)
    {
        try {
            $this->parameterBuilder->setReferer($invalidReferer);
            $this->fail('A InvalidParamException was expected to occur when the shopurl param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter referer is not valid.', $e->getMessage());
        }
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
     * @param $invalidRevision mixed
     */
    public function testExceptionIsThrownIfRevisionIsInvalid($invalidRevision)
    {
        try {
            $this->parameterBuilder->setRevision($invalidRevision);
            $this->fail('A InvalidParamException was expected to occur when the shopurl param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter revision is not valid.', $e->getMessage());
        }
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

    public function invalidQueryProvider()
    {
        return [
            'query is an integer' => [555],
            'query is an array' => [['aaaah']],
            'query is an object' => [new \stdClass()],
        ];
    }

    /**
     * @dataProvider invalidQueryProvider
     * @param $invalidQuery mixed
     */
    public function testExceptionIsThrownIfQueryIsInvalid($invalidQuery)
    {
        try {
            $this->parameterBuilder->setQuery($invalidQuery);
            $this->fail('A InvalidParamException was expected to occur when the shopurl param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter query is not valid.', $e->getMessage());
        }
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
     * @param $invalidAttributeName mixed
     * @param $invalidAttributeValue mixed
     * @param $invalidSpecifier mixed
     */
    public function testExceptionIsThrownIfAttributeIsInvalid(
        $invalidAttributeName,
        $invalidAttributeValue,
        $invalidSpecifier
    ) {
        try {
            $this->parameterBuilder->addAttribute($invalidAttributeName, $invalidAttributeValue, $invalidSpecifier);
            $this->fail('A InvalidParamException was expected to occur when the shopurl param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter attrib is not valid.', $e->getMessage());
        }
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
            'normal order' => [OrderType::EXPENSIVE_FIRST],
            'other order' => [OrderType::RELEVANCE],
            'more different order' => [OrderType::NEWEST_PRODUCTS_FIRST]
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

    public function invalidOrderProvider()
    {
        return [
        'order is not an valid order' => ['order by blubbergurken'],
        'order is an integer' => [1337],
        'order is an array' => [['blubb']],
        'order is an object' => [new \stdClass()],
        'order with the last char being lost' => ['salesfrequency DES'],
    ];
    }

    /**
     * @dataProvider invalidOrderProvider
     * @param $invalidOrder mixed
     */
    public function testExceptionIsThrownIfOrderIsInvalid($invalidOrder)
    {
        try {
            $this->parameterBuilder->setOrder($invalidOrder);
            $this->fail('A InvalidParamException was expected to occur when the shopurl param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter order is not valid.', $e->getMessage());
        }
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

    public function invalidPropertyProvider()
    {
        return [
            'property is an integer' => [561],
            'property is an array' => [['propertyBest']],
            'property is an object' => [new \stdClass()],
        ];
    }

    /**
     * @dataProvider invalidPropertyProvider
     * @param $invalidProperty mixed
     */
    public function testExceptionIsThrownIfPropertyIsInvalid($invalidProperty)
    {
        try {
            $this->parameterBuilder->addProperty($invalidProperty);
            $this->fail('A InvalidParamException was expected to occur when the properties param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter properties is not valid.', $e->getMessage());
        }
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
        $expectedPushAttrib = [$expectedPushAttribName => [$expectedPushAttribValue => $factor]];
        $paramName = ParameterBuilder::PUSH_ATTRIB;

        $this->parameterBuilder->addPushAttrib($expectedPushAttribName, $expectedPushAttribValue, $factor);
        $pushAttrib = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedPushAttrib, $pushAttrib);
    }

    public function invalidPushAttribProvider()
    {
        return [
            'pushAttrib name is an array' => [['Tom Tailor'], '50', 3],
            'pushAttrib value is an array' => ['Tom Tailor', ['50'], 3],
            'pushAttrib specifier is an array' => ['Tom Tailor', '50', [3]],
            'pushAttrib name is an integer' => [1337, '50', 3],
            'pushAttrib value is an integer' => ['Tom Tailor', 50, 3],
            'pushAttrib name is an object' => [new \stdClass(), '50', 3],
            'pushAttrib value is an object' => ['Tom Tailor', new \stdClass(), 3],
            'pushAttrib specifier is an object' => ['Tom Tailor', '50', new \stdClass()],
        ];
    }

    /**
     * @dataProvider invalidPushAttribProvider
     * @param $invalidPushAttribName mixed
     * @param $invalidPushAttribValue mixed
     * @param $invalidFactor mixed
     */
    public function testExceptionIsThrownIfPushAttribIsInvalid(
        $invalidPushAttribName,
        $invalidPushAttribValue,
        $invalidFactor
    ) {
        try {
            $this->parameterBuilder->addPushAttrib($invalidPushAttribName, $invalidPushAttribValue, $invalidFactor);
            $this->fail('A InvalidParamException was expected to occur when the pushAttrib param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter pushAttrib is not valid.', $e->getMessage());
        }
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

    public function invalidCountProvider()
    {
        return [
            'count is a string' => ['abc'],
            'count is an array' => [['aaaah']],
            'count is an object' => [new \stdClass()],
        ];
    }

    /**
     * @dataProvider invalidCountProvider
     * @param $invalidCount mixed
     */
    public function testExceptionIsThrownIfCountIsInvalid($invalidCount)
    {
        try {
            $this->parameterBuilder->setCount($invalidCount);
            $this->fail('A InvalidParamException was expected to occur when the count param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter count is not valid.', $e->getMessage());
        }
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

    public function invalidFirstProvider()
    {
        return [
            'count is a string' => ['abc'],
            'count is an array' => [['aaaah']],
            'count is an object' => [new \stdClass()],
        ];
    }

    /**
     * @dataProvider invalidFirstProvider
     * @param $invalidFirst mixed
     */
    public function testExceptionIsThrownIfFirstIsInvalid($invalidFirst)
    {
        try {
            $this->parameterBuilder->setFirst($invalidFirst);
            $this->fail('A InvalidParamException was expected to occur when the first param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter first is not valid.', $e->getMessage());
        }
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
     * @dataProvider identifierProvider
     * @param $expectedIdentifier string
     */
    public function testSetIdentifierWillSetItInAValidFormat($expectedIdentifier)
    {
        $paramName = ParameterBuilder::IDENTIFIER;

        $this->parameterBuilder->setIdentifier($expectedIdentifier);
        $identifier = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedIdentifier, $identifier);
    }

    public function invalidIdentifierProvider()
    {
        return [
            'identifier is an integer' => [123],
            'identifier is an array' => [['aaaah']],
            'identifier is an object' => [new \stdClass()],
        ];
    }

    /**
     * @dataProvider invalidIdentifierProvider
     * @param $invalidIdentifier mixed
     */
    public function testExceptionIsThrownIfIdentifierIsInvalid($invalidIdentifier)
    {
        try {
            $this->parameterBuilder->setIdentifier($invalidIdentifier);
            $this->fail('A InvalidParamException was expected to occur when the identifier param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter identifier is not valid.', $e->getMessage());
        }
    }

    /**
     * Returns some group that might be set by users. Invalid groups are not tested since they should not
     * even get to this point.
     *
     * @return array
     */
    public function groupProvider()
    {
        return [
            'normal group' => ['NDEmNgEN'],
            'other group' => ['HaKeLfGN'],
            'more different group' => ['ThisGroupMayBeValid']
        ];
    }

    /**
     * @dataProvider groupProvider
     * @param $expectedGroupValue string
     */
    public function testAddGroupWillSetItInAValidFormat($expectedGroupValue)
    {
        $expectedGroup = ['' => $expectedGroupValue];
        $paramName = ParameterBuilder::GROUP;

        $this->parameterBuilder->addGroup($expectedGroupValue);
        $group = $this->parameterBuilder->getParam($paramName);

        $this->assertEquals($expectedGroup, $group);
    }

    public function invalidGroupProvider()
    {
        return [
            'group is an integer' => [123],
            'group is an array' => [['aaaah']],
            'group is an object' => [new \stdClass()],
        ];
    }

    /**
     * @dataProvider invalidGroupProvider
     * @param $invalidIdentifier mixed
     */
    public function testExceptionIsThrownIfGroupIsInvalid($invalidIdentifier)
    {
        try {
            $this->parameterBuilder->addGroup($invalidIdentifier);
            $this->fail('A InvalidParamException was expected to occur when the group param is invalid.');
        } catch (InvalidParamException $e) {
            $this->assertEquals('Parameter group is not valid.', $e->getMessage());
        }
    }

    /**
     * Returns some individual param that might be set by users. The individual param is not validated!
     *
     * @return array
     */
    public function individualParamProvider()
    {
        return [
            'normal individual param' => ['shoes', 'abc', 'set'],
            'other individual param' => ['really good shoes', 'what?!', 'set'],
            'more different individual param' => ['best & *_\' shoes &copy; ever!', 'very very FuNnY!!!', 'set']
        ];
    }

    /**
     * @dataProvider individualParamProvider
     * @param $expectedIndividualParamKey string
     * @param $expectedIndividualParamValue string
     * @param $expectedMethod string
     */
    public function testAddIndividualParamWillSetItInAValidFormat(
        $expectedIndividualParamKey,
                                                                  $expectedIndividualParamValue,
        $expectedMethod
    ) {
        $this->parameterBuilder->addIndividualParam(
            $expectedIndividualParamKey,
            $expectedIndividualParamValue,
            $expectedMethod
        );
        $individualParam = $this->parameterBuilder->getParam($expectedIndividualParamKey);

        $this->assertEquals($expectedIndividualParamValue, $individualParam);
    }

    public function testAddingParamsWithARandomValueWillThrowAnException()
    {
        $expectedKey = 'someKey';
        $expectedValue = 'someValue';
        $expectedInvalidMethod = 'invalidMethod';

        try {
            $this->parameterBuilder->addIndividualParam($expectedKey, $expectedValue, $expectedInvalidMethod);
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Unknown method type.', $e->getMessage());
        }
    }
}
