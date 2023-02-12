<?php

namespace App\WebScraper\Adjuster;

interface AdjusterServiceInterface
{
    /**
     * Adjusts data.
     */
    public function adjustData(array $data, array $context): array;
}