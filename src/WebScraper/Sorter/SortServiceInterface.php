<?php

namespace App\WebScraper\Sorter;

interface SortServiceInterface
{
    /**
     * Sorts data.
     */
    public function sortData(array $data, array $context): array;
}