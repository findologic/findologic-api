<?php

namespace FINDOLOGIC\Objects;

use FINDOLOGIC\Objects\JsonResponseObjects\Suggestion;

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
     * @param $response
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
     * Filter the suggestions base on the specified block types.
     *
     * @param array $blockTypes The expected block types.
     * @return Suggestion[]
     */
    public function getFilteredSuggestions($blockTypes)
    {
        return array_values(array_filter($this->getSuggestions(), function ($suggestion) use ($blockTypes) {
            return in_array($suggestion->getBlock(), $blockTypes);
        }));
    }
}
