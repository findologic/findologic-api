<?php

namespace FINDOLOGIC\Api\Objects;

use FINDOLOGIC\Api\Objects\JsonResponseObjects\Suggestion;

/**
 * Is used for suggestion requests with JSON response and Smart Suggest v3 only!
 *
 * Class JsonResponse
 * @package FINDOLOGIC\Objects
 */
class JsonResponse
{
    /** @var Suggestion[] $suggestions */
    private $suggestions;

    /**
     * JsonResponse constructor.
     * @param string $response
     */
    public function __construct($response)
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