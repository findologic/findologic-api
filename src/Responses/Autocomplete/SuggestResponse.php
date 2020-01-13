<?php

namespace FINDOLOGIC\Api\Responses\Autocomplete;

use FINDOLOGIC\Api\Responses\Autocomplete\Properties\Suggestion;
use FINDOLOGIC\Api\Responses\Response;

/**
 * Is used for suggestion requests with JSON response and Smart Suggest v3 only!
 */
class SuggestResponse extends Response
{
    /** @var Suggestion[] */
    private $suggestions = [];

    protected function buildResponseElementInstances($response)
    {
        $suggestions = json_decode($response);

        foreach ($suggestions as $suggestion) {
            $this->suggestions[] = new Suggestion($suggestion);
        }
    }

    /**
     * @return Suggestion[]
     */
    public function getSuggestions()
    {
        return $this->suggestions;
    }

    /**
     * Filter the suggestions based on the specified block types.
     *
     * @param array $blockTypes A list of block types which should be returned from the response.
     * @return Suggestion[]
     */
    public function getFilteredSuggestions(array $blockTypes)
    {
        return array_values(array_filter($this->getSuggestions(), function ($suggestion) use ($blockTypes) {
            /** @var Suggestion $suggestion */
            return in_array($suggestion->getBlock(), $blockTypes);
        }));
    }
}
