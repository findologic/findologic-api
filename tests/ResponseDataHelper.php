<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Tests;

use FINDOLOGIC\Api\Responses\Response;
use InvalidArgumentException;

trait ResponseDataHelper
{
    /**
     * @param class-string|null $responseClass
     * @return Response
     */
    public function getResponseData(
        string $filename = 'demoResponseSuggest.json',
        string $mockPath = '',
        ?string $responseClass = null
    ): Response {
        if (!$responseClass) {
            throw new InvalidArgumentException('Response class name required, but not given');
        }

        $file = __DIR__ . '/Mockdata/' . $mockPath . '/' . $filename;

        // Get contents from a real response locally.
        $realResponseData = file_get_contents($file);
        if (!$realResponseData) {
            throw new InvalidArgumentException(sprintf('Could not open file "%s"', realpath($file)));
        }

        /** @var Response $response */
        $response = new $responseClass($realResponseData);

        return $response;
    }
}
