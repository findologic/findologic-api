<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Requests\Item;

use BadMethodCallException;
use FINDOLOGIC\Api\Requests\Item\ItemUpdateRequest;
use FINDOLOGIC\Api\Tests\TestBase;
use InvalidArgumentException;

class ItemUpdateRequestTest extends TestBase
{
    /**
     * @return array<string, array<string, array<int|string, array<int|string, array<string, array<string, bool>>|string>>>>
     */
    public function itemsVisibleProvider(): array
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
     * @param array<int|string, array<string, string>|string> $items
     * @param array<string, array<string, array<string>>> $expectedRequestBody
     */
    public function testItemsCanBeMarkedAsVisible(array $items, array $expectedRequestBody): void
    {
        $updateRequest = new ItemUpdateRequest();
        foreach ($items as $item) {
            $updateRequest->markVisible($item['productId'], $item['userGroup']);
        }

        $requestBody = json_decode($updateRequest->getBody(), true);

        $this->assertEquals($expectedRequestBody, $requestBody);
    }

    public function testItemsCanBeMarkedAsVisibleAndInvisibleIteratively(): void
    {
        $productId = '123';
        $updateRequest = new ItemUpdateRequest();

        $updateRequest->markVisible($productId);
        $updateRequest->markVisible($productId);
        $updateRequest->markInvisible($productId);

        $requestBody = json_decode($updateRequest->getBody(), true);
        $this->assertFalse($requestBody['update'][$productId]['visible']['']);
    }

    public function testItemVisibilityIsDependentOnTheUserGroup(): void
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

    public function testChangesCanBeReset(): void
    {
        $updateRequest = new ItemUpdateRequest();
        $updateRequest->markInvisible('1234');

        $requestBody = json_decode($updateRequest->getBody(), true);
        $this->assertNotEmpty($requestBody['update']);
        $updateRequest->reset();

        $requestBody = json_decode($updateRequest->getBody(), true);
        $this->assertEmpty($requestBody['update']);
    }

    public function testChangesCanBeResetPerItem(): void
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

    public function testItemNotExistsFails(): void
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

    public function testAddUnknownChangeThrowsAnError(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown change type provided');

        $itemId = '1234';

        $updateRequest = new ItemUpdateRequest();
        $updateRequest->markVisible($itemId);
        $item = $updateRequest->getItem($itemId);

        $item->getOrCreateChange(69);
    }

    /**
     * @return array<string, array<string, int|string>>
     */
    public function unsupportedSetterProvider(): array
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
     * @param string|int $argument
     */
    public function testUnsupportedSettersThrowErrors(
        string $methodName,
        $argument,
        string $expectedExceptionMessage
    ): void {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $updateRequest = new ItemUpdateRequest();
        $updateRequest->{$methodName}($argument);
    }

    public function testOutputAdapterAlwaysReturnsNull(): void
    {
        $updateRequest = new ItemUpdateRequest();
        $this->assertNull($updateRequest->getOutputAdapter());
    }
}
