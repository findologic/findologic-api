<?php

namespace FINDOLOGIC\Tests\Objects;

use FINDOLOGIC\Objects\JsonResponse;
use PHPUnit\Framework\TestCase;

class JsonResponseTest extends TestCase
{
    /**
     * Will use a real response that could come from a request. It returns the Object.
     *
     * @param string $filename
     * @return JsonResponse
     */
    public function getRealResponseData($filename = 'demoResponseSuggest.json')
    {
        // Get contents from a real response locally.
        $realResponseData = file_get_contents(__DIR__ . '/../../Mockdata/' . $filename);
        return new JsonResponse($realResponseData);
    }

    public function testResponseWillReturnValidData()
    {
        $response = $this->getRealResponseData();

        // TODO: Add tests
        //foreach ($response->getSuggestions() as $suggestion) {
        //    $this->assertEquals('', $suggestion->getLabel());
        //}
    }
}