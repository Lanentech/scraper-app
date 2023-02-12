<?php

namespace App\WebScraper\Sorter;

class SortService implements SortServiceInterface
{
    /**
     * @param SorterInterface[] $sorters
     */
    public function __construct(
        private readonly iterable $sorters,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function sortData(array $data, array $context): array
    {
        foreach ($this->sorters as $sorter) {
            if ($sorter->supports($data, $context)) {
                $data = $sorter->sort($data, $context);
            }
        }

        return $data;
    }
}