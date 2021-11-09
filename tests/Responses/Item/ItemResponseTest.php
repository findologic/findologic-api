<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Responses\Item;

use FINDOLOGIC\Api\Responses\Item\ItemUpdateResponse;
use FINDOLOGIC\Api\Tests\TestBase;

class ItemResponseTest extends TestBase
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function itemResponseProvider(): array
    {
        return [
            'response one error' => [
                'rawResponse' => $this->getMockResponse('Item/demoResponse.json'),
                'expectedItemErrors' => [
                    '123' => ['Does not exist.']
                ]
            ],
            'response with multiple errors' => [
                'rawResponse' => $this->getMockResponse('Item/response_with_item_errors.json'),
                'expectedItemErrors' => [
                    '123' => ['Does not exist.'],
                    'abc' => [
                        'New visible value for usergroup "foobar" has been ignored, because that usergroup does not exist.',
                        'New price value for usergroup "foobar" has been ignored, because that usergroup does not exist.'
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider itemResponseProvider
     * @param mixed $expectedItemErrors
     */
    public function testErrorsCanBeFetched(string $rawResponse, $expectedItemErrors): void
    {
        $response = new ItemUpdateResponse($rawResponse);

        $this->assertTrue($response->hasErrors());
        $this->assertCount(count($expectedItemErrors), $response->getErrors());

        foreach ($response->getErrors() as $itemError) {
            $this->assertSame($expectedItemErrors[$itemError->getId()], $itemError->getReasons());
        }
    }

    public function testResponseWithoutErrors(): void
    {
        $response = new ItemUpdateResponse($this->getMockResponse('Item/response_without_errors.json'));

        $this->assertEmpty($response->getErrors());
        $this->assertFalse($response->hasErrors());
    }
}
