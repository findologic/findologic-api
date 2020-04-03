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
        ALPHABETICAL_ASCENDING = self::FIELD_LABEL . ' ' . self::DIRECTION_ASCENDING,
        ALPHABETICAL_DESCENDING = self::FIELD_LABEL . ' ' . self::DIRECTION_DESCENDING,
        TOP_SELLERS_FIRST = self::FIELD_SALES_FREQUENCY . ' ' . self::DIRECTION_DESCENDING,
        TOP_SELLERS_LAST = self::FIELD_SALES_FREQUENCY . ' ' . self::DIRECTION_ASCENDING,
        NEWEST_FIRST = self::FIELD_DATE_ADDED . ' ' . self::DIRECTION_DESCENDING,
        NEWEST_LAST = self::FIELD_DATE_ADDED . ' ' . self::DIRECTION_ASCENDING;

    /**
     * Builds a custom order parameter.
     *
     * @param string $field
     * @param bool $relevanceBased
     * @param string $direction
     * @return string
     */
    public static function buildOrder($field, $relevanceBased = true, $direction = self::DIRECTION_ASCENDING)
    {
        $dynamic = $relevanceBased ? ' dynamic' : '';
        return sprintf('%s%s %s', $field, $dynamic, $direction);
    }

    public static function getConstants()
    {
        return [
            'RELEVANCE' => self::RELEVANCE,
            'PRICE_ASCENDING' => self::PRICE_ASCENDING,
            'PRICE_DESCENDING' => self::PRICE_DESCENDING,
            'ALPHABETICAL_ASCENDING' => self::ALPHABETICAL_ASCENDING,
            'ALPHABETICAL_DESCENDING' => self::ALPHABETICAL_DESCENDING,
            'TOP_SELLERS_FIRST' => self::TOP_SELLERS_FIRST,
            'TOP_SELLERS_DYNAMIC_FIRST' => self::buildOrder(self::FIELD_SALES_FREQUENCY, true, self::DIRECTION_DESCENDING),
            'TOP_SELLERS_LAST' => self::TOP_SELLERS_LAST,
            'TOP_SELLERS_DYNAMIC_LAST' => self::buildOrder(self::FIELD_SALES_FREQUENCY),
            'NEWEST_FIRST' => self::NEWEST_FIRST,
            'NEWEST_LAST' => self::NEWEST_LAST,
        ];
    }
}
