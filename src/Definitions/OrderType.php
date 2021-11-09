<?php

namespace FINDOLOGIC\Api\Definitions;

class OrderType extends Definition
{
    public const FIELD_PRICE = 'price';
    public const FIELD_LABEL = 'label';
    public const FIELD_SALES_FREQUENCY = 'salesfrequency';
    public const FIELD_DATE_ADDED = 'dateadded';
    public const FIELD_SHOPSORT = 'shopsort';

    public const DIRECTION_ASCENDING = 'ASC';
    public const DIRECTION_DESCENDING = 'DESC';

    public const DEFAULT_SORT = 'rank';

    public const
        RELEVANCE = self::DEFAULT_SORT,
        PRICE_ASCENDING = self::FIELD_PRICE . ' ' . self::DIRECTION_ASCENDING,
        PRICE_DESCENDING = self::FIELD_PRICE . ' ' . self::DIRECTION_DESCENDING,
        ALPHABETICAL_ASCENDING = self::FIELD_LABEL . ' ' . self::DIRECTION_ASCENDING,
        ALPHABETICAL_DESCENDING = self::FIELD_LABEL . ' ' . self::DIRECTION_DESCENDING,
        TOP_SELLERS_FIRST = self::FIELD_SALES_FREQUENCY . ' ' . self::DIRECTION_DESCENDING,
        TOP_SELLERS_LAST = self::FIELD_SALES_FREQUENCY . ' ' . self::DIRECTION_ASCENDING,
        NEWEST_FIRST = self::FIELD_DATE_ADDED . ' ' . self::DIRECTION_DESCENDING,
        NEWEST_LAST = self::FIELD_DATE_ADDED . ' ' . self::DIRECTION_ASCENDING,
        SHOPSORT_ASCENDING = self::FIELD_SHOPSORT . ' ' . self::DIRECTION_ASCENDING,
        SHOPSORT_DESCENDING = self::FIELD_SHOPSORT . ' ' . self::DIRECTION_DESCENDING;

    public static function buildOrder(
        string $field,
        bool $relevanceBased = true,
        string $direction = self::DIRECTION_ASCENDING
    ): string {
        $dynamic = $relevanceBased ? ' dynamic' : '';

        return sprintf('%s%s %s', $field, $dynamic, $direction);
    }

    public static function getConstants(): array
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
            'SHOPSORT_ASCENDING' => self::SHOPSORT_ASCENDING,
            'SHOPSORT_DESCENDING' => self::SHOPSORT_DESCENDING,
        ];
    }
}
