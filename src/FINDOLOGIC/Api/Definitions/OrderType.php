<?php

namespace FINDOLOGIC\Api\Definitions;

class OrderType extends Definition
{
    const
        FIELD_PRICE = 'price',
        FIELD_LABEL = 'label',
        FIELD_SALES_FREQUENCY = 'salesfrequency',
        FIELD_DATE_ADDED = 'dateadded';

    const
        DIRECTION_ASCENDING = 'ASC',
        DIRECTION_DESCENDING = 'DESC';

    const DEFAULT_SORT = 'rank';

    const
        RELEVANCE = self::DEFAULT_SORT,
        PRICE_ASCENDING = self::FIELD_PRICE . ' ' . self::DIRECTION_ASCENDING,
        PRICE_DESCENDING = self::FIELD_PRICE . ' ' . self::DIRECTION_DESCENDING,
        ALPHABETICAL = self::FIELD_LABEL . ' ' . self::DIRECTION_ASCENDING,
        TOP_SELLERS_FIRST = self::FIELD_SALES_FREQUENCY . ' ' . self::DIRECTION_DESCENDING,
        NEWEST_FIRST = self::FIELD_DATE_ADDED . ' ' . self::DIRECTION_DESCENDING;

    /**
     * Builds a custom order parameter.
     *
     * @param string $field
     * @param bool $relevanceBased
     * @param string $direction
     * @return string
     */
    public function buildOrder($field, $relevanceBased = true, $direction = self::DIRECTION_ASCENDING)
    {
        $dynamic = $relevanceBased ? ' dynamic' : '';
        return sprintf('%s%s %s', $field, $dynamic, $direction);
    }
}
