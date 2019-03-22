<?php

namespace FINDOLOGIC\Api\ResponseObjects\Json;

use FINDOLOGIC\Api\ResponseObjects\Json\Properties\Suggestion;
use FINDOLOGIC\Api\ResponseObjects\Response;

/**
 * Is used for suggestion requests with JSON response and Smart Suggest v3 only!
 *
 * Class JsonResponse
 * @package FINDOLOGIC\Api\ResponseObjects
 */
class SuggestResponse extends Response
{
    /** @var Suggestion[] $suggestions */
    private $suggestions;

    /**
     * @inheritdoc
     */
    public function __construct($response, $responseTime = null)
    {
        $this->responseTime = $responseTime;
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
