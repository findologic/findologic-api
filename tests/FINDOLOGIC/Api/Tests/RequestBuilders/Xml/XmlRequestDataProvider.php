<?php

namespace FINDOLOGIC\Api\Tests\RequestBuilders\Xml;

/**
 * This trait holds data providers for search and navigation requests.
 */
trait XmlRequestDataProvider
{
    public function queryProvider()
    {
        return [
            'some random string can be set' => [
                'string' => 'something',
                'expectedResult' => urlencode('something'),
            ],
            'an empty string an be set' => [
                'string' => '',
                'expectedResult' => urlencode(''),
            ],
            'special characters in string should be url encoded' => [
                'string' => '/ /',
                'expectedResult' => urlencode('/ /'),
            ],
        ];
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

    public function shopurlProvider()
    {
        return [
            'normal shopurl' => [
                'shopurl' => 'www.poop.com',
                'expectedResult' => urlencode('www.poop.com'),
            ],
            'other shopurl' => [
                'shopurl' => 'www.shop.co.de',
                'expectedResult' => urlencode('www.shop.co.de'),
            ],
            'more different shopurl' => [
                'shopurl' => 'blubbergurken.de/shop',
                'expectedResult' => urlencode('blubbergurken.de/shop'),
            ]
        ];
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
                'expectedResult' => urlencode('2001:0db8:85a3:0000:0000:8a2e:0370:7334'),
            ],
        ];
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

    public function refererProvider()
    {
        return [
            'normal referer' => [
                'referer' => 'https://www.somedomain.de/best-category',
                'expectedResult' => urlencode('https://www.somedomain.de/best-category'),
            ],
            'other referer' => [
                'referer' => 'http://this-is-an-unsecure-domain.com/unsecure',
                'expectedResult' => urlencode('http://this-is-an-unsecure-domain.com/unsecure'),
            ],
            'more different referer' => [
                'referer' => 'http://www.domain.ru/domains',
                'expectedResult' => urlencode('http://www.domain.ru/domains'),
            ]
        ];
    }

    public function invalidRefererProvider()
    {
        return [
            'referer is not a referer' => ['invalidReferer'],
            'referer is an integer' => [5],
            'referer is an array' => [['127.0.0.1']],
            'referer is an object' => [new \stdClass()],
            'referer with wrong protocol call' => ['http::///www.domain.ru/domains'],
        ];
    }

    public function revisionProvider()
    {
        return [
            'normal revision' => ['1.0.0'],
            'other revision' => ['5.1.3'],
            'more different revision' => ['55.3.11']
        ];
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

    public function attributeProvider()
    {
        return [
            'normal attribute' => ['vendor', 'TomTailor', null],
            'other attribute' => ['Material', 'Leather', null],
            'more different attribute' => ['price', '200.0', 'max']
        ];
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

    public function orderProvider()
    {
        return [
            'normal order' => [
                'order' => 'price DESC',
                'expectedResult' => urlencode('price DESC'),
            ],
            'other order' => [
                'order' => 'rank',
                'expectedResult' => urlencode('rank'),
            ],
            'more different order' => [
                'order' => 'dateadded DESC',
                'expectedResult' => urlencode('dateadded DESC'),
            ],
        ];
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

    public function propertyProvider()
    {
        return [
            'normal property' => ['ordernumber'],
            'other property' => ['detailedDescription'],
            'more different property' => ['articleName']
        ];
    }
}
