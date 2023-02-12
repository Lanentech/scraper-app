<?php

namespace App\WebScraper\Client;

use App\WebScraper\Source\SourceInterface;

interface ClientInterface
{
    /**
     * Returns the scraped data within an array.
     */
    public function performScrape(SourceInterface $source): array;
}