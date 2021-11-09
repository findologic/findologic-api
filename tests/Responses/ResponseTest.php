<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests\Responses;

use FINDOLOGIC\Api\Requests\Autocomplete\SuggestRequest;
use FINDOLOGIC\Api\Requests\Item\ItemUpdateRequest;
use FINDOLOGIC\Api\Requests\Request;
use FINDOLOGIC\Api\Requests\SearchNavigation\NavigationRequest;
use FINDOLOGIC\Api\Requests\SearchNavigation\SearchRequest;
use FINDOLOGIC\Api\Responses\Autocomplete\SuggestResponse;
use FINDOLOGIC\Api\Responses\Html\GenericHtmlResponse;
use FINDOLOGIC\Api\Responses\Item\ItemUpdateResponse;
use FINDOLOGIC\Api\Responses\Json10\Json10Response;
use FINDOLOGIC\Api\Responses\Response;
use FINDOLOGIC\Api\Responses\Xml21\Xml21Response;
use FINDOLOGIC\Api\Tests\TestBase;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;

class ResponseTest extends TestBase
{
    public function testUnknownResponseWillThrowAnException(): void
    {
        $expectedOutputAdapter = 'HTML_4.20';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Unknown or invalid outputAdapter "%s"', $expectedOutputAdapter));

        /** @var SearchRequest|MockObject $request */
        $request = $this->getMockBuilder(SearchRequest::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getOutputAdapter'])
            ->getMock();
        $request->expects($this->any())->method('getOutputAdapter')->willReturn($expectedOutputAdapter);

        Response::buildInstance($request, new GuzzleResponse(), null, null);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function availableResponseProvider(): array
    {
        $expectedSuggestResponse = $this->getMockResponse('Autocomplete/demoResponseSuggest.json');
        $expectedHtmlResponse = $this->getMockResponse('Html/demoResponse.html');
        $expectedXml21Response = $this->getMockResponse('Xml21/demoResponse.xml');
        $expectedJson10Response = $this->getMockResponse('Json10/demoResponse.json');
        $expectedItemUpdateResponse = $this->getMockResponse('Item/demoResponse.json');

        return [
            'suggest response' => [
                'response' => new SuggestResponse($expectedSuggestResponse),
                'expectedRawResponse' => $expectedSuggestResponse
            ],
            'HTML response' => [
                'response' => new GenericHtmlResponse($expectedHtmlResponse),
                'expectedRawResponse' => $expectedHtmlResponse
            ],
            'XML 2.1 response' => [
                'response' => new Xml21Response($expectedXml21Response),
                'expectedRawResponse' => $expectedXml21Response
            ],
            'JSON 1.0 response' => [
                'response' => new Json10Response($expectedJson10Response),
                'expectedRawResponse' => $expectedJson10Response
            ],
            'ItemUpdate response' => [
                'response' => new ItemUpdateResponse($expectedItemUpdateResponse),
                'expectedRawResponse' => $expectedItemUpdateResponse
            ],
        ];
    }

    /**
     * @dataProvider availableResponseProvider
     */
    public function testGettingRawResponseAsStringReturnsItAsExpected(
        Response $response,
        string $expectedRawResponse
    ): void {
        $this->assertEquals($expectedRawResponse, $response->getRawResponse());
    }

    /**
     * @return array<string, array<string, class-string|Request|ResponseInterface>>
     */
    public function responseProvider(): array
    {
        return [
            'search request' => [
                'request' => new SearchRequest(),
                'response' => new GuzzleResponse(200, [], $this->getMockResponse('Xml21/demoResponse.xml')),
                'expectedResponseInstance' => Xml21Response::class
            ],
            'navigation request' => [
                'request' => new NavigationRequest(),
                'response' => new GuzzleResponse(200, [], $this->getMockResponse('Xml21/demoResponse.xml')),
                'expectedResponseInstance' => Xml21Response::class
            ],
            'suggest request' => [
                'request' => new SuggestRequest(),
                'response' => new GuzzleResponse(
                    200,
                    [],
                    $this->getMockResponse('Autocomplete/demoResponseSuggest.json')
                ),
                'expectedResponseInstance' => SuggestResponse::class
            ],
            'item update request' => [
                'request' => new ItemUpdateRequest(),
                'response' => new GuzzleResponse(200, [], $this->getMockResponse('Item/demoResponse.json')),
                'expectedResponseInstance' => ItemUpdateResponse::class
            ],
        ];
    }

    /**
     * @dataProvider responseProvider
     */
    public function testResponseDependsOnRequestInstance(
        Request $request,
        ResponseInterface $response,
        string $expectedResponseInstance
    ): void {
        $response = Response::buildInstance($request, $response);

        $this->assertInstanceOf($expectedResponseInstance, $response);
    }
}
