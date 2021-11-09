<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Autocomplete;

use FINDOLOGIC\Api\Responses\Autocomplete\Properties\Suggestion;
use FINDOLOGIC\Api\Responses\Response;

/**
 * Is used for suggestion requests with JSON response and Smart Suggest v3 only!
 */
class SuggestResponse extends Response
{
    /** @var Suggestion[] */
    private array $suggestions = [];

    protected function buildResponseElementInstances(string $response): void
    {
        $suggestions = json_decode($response, true);

        foreach ($suggestions as $suggestion) {
            $this->suggestions[] = new Suggestion($suggestion);
        }
    }

    /**
     * @return Suggestion[]
     */
    public function getSuggestions(): array
    {
        return $this->suggestions;
    }

    /**
     * Filter the suggestions based on the specified block types.
     *
     * @param string[] $blockTypes A list of block types which should be returned from the response.
     * @return Suggestion[]
     */
    public function getFilteredSuggestions(array $blockTypes): array
    {
        return array_values(array_filter($this->getSuggestions(), function ($suggestion) use ($blockTypes) {
            return in_array($suggestion->getBlock(), $blockTypes, true);
        }));
    }
}
