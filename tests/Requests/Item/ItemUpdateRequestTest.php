<?php

namespace FINDOLOGIC\Api\Tests\Requests\Item;

use FINDOLOGIC\Api\Requests\Item\ItemUpdateRequest;
use FINDOLOGIC\Api\Tests\TestBase;

class ItemUpdateRequestTest extends TestBase
{
    public function itemsVisibleProvider()
    {
        return [
            'make single item visible' => [
                'items' => [
                    [
                        'productId' => '1234',
                        'userGroup' => ''
                    ]
                ],
                'expectedRequestBody' => [
                    'update' => [
                        '1234' => [
                            'visible' => [
                                '' => true
                            ]
                        ]
                    ]
                ],
            ],
            'make multiple items visible' => [
                'items' => [
                    [
                        'productId' => '1234',
                        'userGroup' => ''
                    ],
                    [
                        'productId' => '12345',
                        'userGroup' => ''
                    ]
                ],
                'expectedRequestBody' => [
                    'update' => [
                        '1234' => [
                            'visible' => [
                                '' => true
                            ]
                        ],
                        '12345' => [
                            'visible' => [
                                '' => true
                            ]
                        ]
                    ]
                ],
            ],
        ];
    }

    /**
     * @dataProvider itemsVisibleProvider
     */
    public function testItemsCanBeMarkedAsActive(array $items, array $expectedRequestBody)
    {
        $updateRequest = new ItemUpdateRequest();
        foreach ($items as $item) {
            $updateRequest->markVisible($item['productId'], $item['userGroup']);
        }

        $actualBody = json_decode($updateRequest->getBody(), true);

        $this->assertEquals($expectedRequestBody, $actualBody);
    }
}
