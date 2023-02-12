<?php

namespace App\Tests\Traits;

use App\WebScraper\Sorter\PriceSorter;
use App\WebScraper\Source\SourceInterface;
use App\WebScraper\Source\WirelessLogicSource;

trait MockContextTrait
{
    public function getMockContextArray(
        string $format = 'json',
        string $priceSort = PriceSorter::SORT_DESCENDING,
        string $source = WirelessLogicSource::IDENTIFIER,
        SourceInterface $sourceInstance = new WirelessLogicSource(),
    ): array {
        return [
            'format' => $format,
            'price-sort' => $priceSort,
            'source' => $source,
            'sourceInstance' => $sourceInstance,
        ];
    }
}