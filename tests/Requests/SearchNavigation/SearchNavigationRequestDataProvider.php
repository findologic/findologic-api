<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Requests\SearchNavigation;

use FINDOLOGIC\Api\Requests\SearchNavigation\SearchNavigationRequest;

/**
 * This trait holds data providers and helper functions for search and navigation requests.
 */
trait SearchNavigationRequestDataProvider
{
    protected function setRequiredParamsForSearchNavigationRequest(
        SearchNavigationRequest $searchNavigationRequest
    ): void {
        $searchNavigationRequest
            ->setShopUrl('blubbergurken.io')
            ->setUserIp('127.0.0.1')
            ->setReferer('https://blubbergurken.io/blubbergurken-sale/')
            ->setRevision('2.5.10');
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function queryProvider(): array
    {
        return [
            'some random string can be set' => [
                'expectedQuery' => 'something',
            ],
            'an empty string can be set' => [
                'expectedQuery' => '',
            ],
            'special characters in string should be set' => [
                'expectedQuery' => '/ /',
            ],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function shopkeyProvider(): array
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
     * @return array<string, array<int, string>>
     */
    public function invalidShopkeyProvider(): array
    {
        return [
            'shopkey is not a shopkey' => ['invalidShopkey'],
            'shopkey length not optimal' => ['INVALIDAF'],
            'shopkey contains invalid characters' => ['80AB18D4BE2654R78244106AD315DC2C'],
            'shopkey is lowercased' => ['80ab18d4be2654r78244106ad315dc2c'],
            'shopkey contains spaces' => ['80AB18D4BE2654A7 8244106AD315DC2C'],
            'shopkey contains special characters' => ['AAAAAA.AAAAAAÄAAAAAAAAAAAAAAAAA_'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function shopurlProvider(): array
    {
        return [
            'normal shopurl' => [
                'expectedShopurl' => 'www.poop.com',
            ],
            'other shopurl' => [
                'expectedShopurl' => 'www.shop.co.de',
            ],
            'more different shopurl' => [
                'expectedShopurl' => 'blubbergurken.de/shop',
            ]
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function invalidShopurlProvider(): array
    {
        return [
            'shopurl is not an url' => ['invalidShopurl'],
            'shopurl with missing slashes after protocol' => ['http:www.example.com/main.html'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function useripProvider(): array
    {
        return [
            'normal userip' => [
                'expectedUserip' => '127.0.0.1',
            ],
            'other userip' => [
                'expectedUserip' => '183.12.42.33',
            ],
            'more different userip' => [
                'expectedUserip' => '255.255.255.255',
            ],
            'ipv6 userip' => [
                'expectedUserip' => '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
            ],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function invalidUseripProvider(): array
    {
        return [
            'userip is not an ip' => ['invalidIp'],
            'userip with too many numbers' => ['1.10.100.1000'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function refererProvider(): array
    {
        return [
            'normal referer' => [
                'expectedReferer' => 'https://www.somedomain.de/best-category',
            ],
            'other referer' => [
                'expectedReferer' => 'http://this-is-an-unsecure-domain.com/unsecure',
            ],
            'more different referer' => [
                'expectedReferer' => 'http://www.domain.ru/domains',
            ]
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function invalidRefererProvider(): array
    {
        return [
            'referer is not a referer' => ['invalidReferer'],
            'referer with wrong protocol call' => ['http::///www.domain.ru/domains'],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function revisionProvider(): array
    {
        return [
            'normal revision' => ['1.0.0'],
            'other revision' => ['5.1.3'],
            'more different revision' => ['55.3.11'],
            'release candidate' => ['1.4.3-rc.1'],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function invalidRevisionProvider(): array
    {
        return [
            'revision is not a revision' => ['invalidRevision'],
            'revision with a dot after the number' => ['1.'],
        ];
    }

    /**
     * @return array<string, array<int, string|null>>
     */
    public function attributeProvider(): array
    {
        return [
            'normal attribute' => ['vendor', 'TomTailor', null],
            'other attribute' => ['Material', 'Leather', null],
            'more different attribute' => ['price', '200.0', 'max']
        ];
    }

    /**
     * @return array<string, array<int, array<int, string>|object|string|null>>
     */
    public function invalidAttributeProvider(): array
    {
        return [
            'attribute value is an array' => ['price', ['50'], null],
            'attribute value is an object' => ['price', new \stdClass(), null],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function selectedProvider(): array
    {
        return [
            'normal attribute' => ['vendor', 'TomTailor'],
            'other attribute' => ['Material', 'Leather'],
            'more different attribute' => ['cat', 'Damen']
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function orderProvider(): array
    {
        return [
            'normal order' => [
                'expectedOrder' => 'price DESC',
            ],
            'other order' => [
                'expectedOrder' => 'rank',
            ],
            'more different order' => [
                'expectedOrder' => 'dateadded DESC',
            ],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function invalidOrderProvider(): array
    {
        return [
            'order is not an valid order' => ['order by blubbergurken'],
            'order with the last char being lost' => ['salesfrequency DES'],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function propertyProvider(): array
    {
        return [
            'normal property' => ['ordernumber'],
            'other property' => ['detailedDescription'],
            'more different property' => ['articleName']
        ];
    }

    /**
     * @return array<string, array<int, float|string>>
     */
    public function pushAttribProvider(): array
    {
        return [
            'normal pushAttrib' => ['vendor', 'TomTailor', 0.3],
            'other pushAttrib' => ['Material', 'Leather', 2.1],
            'more different pushAttrib' => ['Color', 'Black', 3.0]
        ];
    }

    /**
     * @return array<string, array<int, array<int, string>|int|object|string>>
     */
    public function invalidPushAttribProvider(): array
    {
        return [
            'pushAttrib value is an array' => ['Tom Tailor', ['50'], 3],
            'pushAttrib value is an object' => ['Tom Tailor', new \stdClass(), 3],
        ];
    }

    /**
     * @return array<string, array<int, int>>
     */
    public function countProvider(): array
    {
        return [
            'normal count' => [20],
            'other count' => [25],
            'more different count' => [256]
        ];
    }

    /**
     * @return array<string, array<int, int>>
     */
    public function invalidCountProvider(): array
    {
        return [
            'count is below zero' => [-5],
        ];
    }

    /**
     * @return array<string, array<int, int>>
     */
    public function firstProvider(): array
    {
        return [
            'normal first' => [20],
            'other first' => [25],
            'more different first' => [256],
        ];
    }

    /**
     * @return array<string, array<int, int>>
     */
    public function invalidFirstProvider(): array
    {
        return [
            'first is below zero' => [-91],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function identifierProvider(): array
    {
        return [
            'normal identifier' => ['15356'],
            'other identifier' => ['a23fv'],
            'more different identifier' => ['019111105-37900']
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function outputAttribProvider(): array
    {
        return [
            'normal outputAttrib' => ['revenue'],
            'other outputAttrib' => ['someAdditionalData'],
            'more different outputAttrib' => ['AndSomethingElseThatCouldBeInteresting']
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function groupProvider(): array
    {
        return [
            'normal group' => ['NDEmNgEN'],
            'other group' => ['HaKeLfGN'],
            'more different group' => ['ThisGroupMayBeValid']
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function individualParamProvider(): array
    {
        return [
            'normal individual param' => ['shoes', 'abc', 'set'],
            'other individual param' => ['really good shoes', 'what?!', 'set'],
            'more different individual param' => ['best & *_\' shoes &copy; ever!', 'very very FuNnY!!!', 'set']
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function userGroupProvider(): array
    {
        return [
            'normal usergroup' => ['NDEmNgEN'],
            'other usergrouphash' => ['HaKeLfGN'],
            'more different usergrouphash' => ['ThisGroupMayBeValid']
        ];
    }
}
