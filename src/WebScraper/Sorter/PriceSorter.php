<?php

namespace App\WebScraper\Sorter;

class PriceSorter implements SorterInterface
{
    public const SORT_ASCENDING = 'ASC';
    public const SORT_DESCENDING = 'DESC';

    /**
     * @inheritDoc
     */
    public function supports(array $data, array $context): bool
    {
        if (empty($data) || !in_array(($context['price-sort'] ?? ''), [self::SORT_ASCENDING, self::SORT_DESCENDING])) {
            return false;
        }

        foreach ($data as $datum) {
            if (array_key_exists('price', $datum)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function sort(array $data, array $context): array
    {
        usort($data, function (array $a, array $b) use ($context) {
            return $context['price-sort'] === self::SORT_DESCENDING
                ? $b['price'] <=> $a['price']
                : $a['price'] <=> $b['price']
            ;
        });

        return $data;
    }
}