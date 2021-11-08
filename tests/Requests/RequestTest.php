<?php

namespace FINDOLOGIC\Api\Tests\Requests;

use FINDOLOGIC\Api\Requests\AlivetestRequest;
use FINDOLOGIC\Api\Requests\Autocomplete\SuggestRequest;
use FINDOLOGIC\Api\Requests\Item\ItemUpdateRequest;
use FINDOLOGIC\Api\Requests\Request;
use FINDOLOGIC\Api\Requests\SearchNavigation\NavigationRequest;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchRequest;
use FINDOLOGIC\Api\Tests\TestBase;
use InvalidArgumentException;

class RequestTest extends TestBase
{
    public function getInstancesProvider()
    {
        return [
            'search request' => [
                'type' => 0,
                'expectedInstance' => SearchRequest::class
            ],
            'navigation request' => [
                'type' => 1,
                'expectedInstance' => NavigationRequest::class
            ],
            'suggestv3 request' => [
                'type' => 2,
                'expectedInstance' => SuggestRequest::class
            ],
            'alivetest request' => [
                'type' => 3,
                'expectedInstance' => AlivetestRequest::class
            ],
            'item update request' => [
                'type' => 4,
                'expectedInstance' => ItemUpdateRequest::class
            ],
        ];
    }

    /**
     * @dataProvider getInstancesProvider
     * @param int $type
     * @param string $expectedInstance
     */
    public function testGetInstanceReturnsProperInstances($type, $expectedInstance)
    {
        $request = Request::getInstance($type);
        $this->assertInstanceOf($expectedInstance, $request);
    }

    public function testExceptionIsThrownForUnknownRequestType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown request type "1337"');

        Request::getInstance(1337);
    }
}
