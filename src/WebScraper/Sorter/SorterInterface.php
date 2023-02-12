<?php

namespace App\WebScraper\Sorter;

interface SorterInterface
{
    /**
     * Evaluates if the sort method should be run.
     */
    public function supports(array $data, array $context): bool;

    /**
     * Sorts the $data passed into the method.
     */
    public function sort(array $data, array $context): array;
}