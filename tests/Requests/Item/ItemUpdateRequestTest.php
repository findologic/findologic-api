<?php

namespace FINDOLOGIC\Api\Tests\Requests\Item;

use BadMethodCallException;
use FINDOLOGIC\Api\Requests\Item\ItemUpdateRequest;
use FINDOLOGIC\Api\Tests\TestBase;
use InvalidArgumentException;

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
    public function testItemsCanBeMarkedAsVisible(array $items, array $expectedRequestBody)
    {
        $updateRequest = new ItemUpdateRequest();
        foreach ($items as $item) {
            $updateRequest->markVisible($item['productId'], $item['userGroup']);
        }

        $requestBody = json_decode($updateRequest->getBody(), true);

        $this->assertEquals($expectedRequestBody, $requestBody);
    }

    public function testItemsCanBeMarkedAsVisibleAndInvisibleIteratively()
    {
        $productId = '123';
        $updateRequest = new ItemUpdateRequest();

        $updateRequest->markVisible($productId);
        $updateRequest->markVisible($productId);
        $updateRequest->markInvisible($productId);

        $requestBody = json_decode($updateRequest->getBody(), true);
        $this->assertFalse($requestBody['update'][$productId]['visible']['']);
    }

    public function testItemVisibilityIsDependentOnTheUserGroup()
    {
        $productId = '1234';
        $defaultUserGroup = '';
        $specialUserGroupWithSpecialPrice = 'special';
        $expectedSpecialPrice = 13.37;
        $notSpecialUserGroupInvisibleItem = 'not_so_special';

        $updateRequest = new ItemUpdateRequest();

        $updateRequest->markVisible($productId, $defaultUserGroup);
        $updateRequest->markVisible($productId, $specialUserGroupWithSpecialPrice);
        $updateRequest->setPrice($productId, $expectedSpecialPrice, $specialUserGroupWithSpecialPrice);
        $updateRequest->markInvisible($productId, $notSpecialUserGroupInvisibleItem);

        $requestBody = json_decode($updateRequest->getBody(), true);
        $this->assertCount(1, $requestBody['update']);
        $this->assertArrayHasKey($productId, $requestBody['update']);

        $productUpdates = $requestBody['update'][$productId];
        $this->assertTrue($productUpdates['visible'][$defaultUserGroup]);
        $this->assertTrue($productUpdates['visible'][$specialUserGroupWithSpecialPrice]);
        $this->assertEquals(
            $expectedSpecialPrice,
            $productUpdates['price'][$specialUserGroupWithSpecialPrice]
        );
        $this->assertFalse($productUpdates['visible'][$notSpecialUserGroupInvisibleItem]);
    }

    public function testChangesCanBeReset()
    {
        $updateRequest = new ItemUpdateRequest();
        $updateRequest->markInvisible('1234');

        $requestBody = json_decode($updateRequest->getBody(), true);
        $this->assertNotEmpty($requestBody['update']);
        $updateRequest->reset();

        $requestBody = json_decode($updateRequest->getBody(), true);
        $this->assertEmpty($requestBody['update']);
    }

    public function testChangesCanBeResetPerItem()
    {
        $itemWithChanges = '1234';
        $itemWithResetChanges = '4321';

        $updateRequest = new ItemUpdateRequest();
        $updateRequest->markInvisible($itemWithChanges);
        $updateRequest->markVisible($itemWithResetChanges);

        $requestBody = json_decode($updateRequest->getBody(), true);
        $this->assertNotEmpty($requestBody['update'][$itemWithResetChanges]);
        $updateRequest->resetItemChanges($itemWithResetChanges);

        $requestBody = json_decode($updateRequest->getBody(), true);
        $this->assertEmpty($requestBody['update'][$itemWithResetChanges]);
        $this->assertNotEmpty($requestBody['update'][$itemWithChanges]);
    }

    public function testItemNotExistsFails()
    {
        $expectedNonExistingItemId = 'i do not exist';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Could not find item with id "%s"',
            $expectedNonExistingItemId
        ));

        $updateRequest = new ItemUpdateRequest();
        $updateRequest->getItem('i do not exist');
    }

    public function testAddUnknownChangeThrowsAnError()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown change type provided');

        $itemId = '1234';

        $updateRequest = new ItemUpdateRequest();
        $updateRequest->markVisible($itemId);
        $item = $updateRequest->getItem($itemId);

        $item->getOrCreateChange(69);
    }

    public function unsupportedSetterProvider()
    {
        return [
            'setQuery' => [
                'methodName' => 'setQuery',
                'argument' => '',
                'expectedExceptionMessage' => 'Parameter "query" is not supported for item updates',
            ],
            'setCount' => [
                'methodName' => 'setCount',
                'argument' => 0,
                'expectedExceptionMessage' => 'Parameter "count" is not supported for item updates',
            ],
            'addGroup' => [
                'methodName' => 'addGroup',
                'argument' => '',
                'expectedExceptionMessage' => 'Parameter "group" is not supported for item updates',
            ],
            'addUserGroup' => [
                'methodName' => 'addUserGroup',
                'argument' => '',
                'expectedExceptionMessage' => 'Parameter "usergroup" is not supported for item updates',
            ],
            'setOutputAdapter' => [
                'methodName' => 'setOutputAdapter',
                'argument' => '',
                'expectedExceptionMessage' => 'Parameter "outputAdapter" is not supported for item updates',
            ],
        ];
    }

    /**
     * @dataProvider unsupportedSetterProvider
     * @param mixed $argument
     */
    public function testUnsupportedSettersThrowErrors(string $methodName, $argument, string $expectedExceptionMessage)
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $updateRequest = new ItemUpdateRequest();
        $updateRequest->{$methodName}($argument);
    }

    public function testOutputAdapterAlwaysReturnsNull()
    {
        $updateRequest = new ItemUpdateRequest();
        $this->assertNull($updateRequest->getOutputAdapter());
    }
}
