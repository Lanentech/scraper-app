<?php

namespace App\Tests\Unit\WebScraper\Sorter;

use App\Tests\Traits\MockContextTrait;
use App\Tests\Traits\MockDataTrait;
use App\WebScraper\Sorter\PriceSorter;
use App\WebScraper\Sorter\SortService;
use PHPUnit\Framework\TestCase;

class SortServiceTest extends TestCase
{
    use MockContextTrait;
    use MockDataTrait;

    public function testSortDataMethodWithNoSortersInjectedIntoConstruct()
    {
        $data = $this->getMockDataArray();
        $sut = new SortService([]);

        $result = $sut->sortData(
            $data,
            $this->getMockContextArray()
        );

        $this->assertEquals($data, $result);
    }

    public function testSortDataMethodWithSortersInjectedIntoConstruct()
    {
        $sut = new SortService([
            new PriceSorter(),
        ]);

        $result = $sut->sortData(
            $this->getMockDataArray(),
            $this->getMockContextArray()
        );

        $this->assertEquals(
            [
                [
                    'option title' => 'Optimum: 2 GB Data - 12 Months',
                    'description' => '2GB data per month including 40 SMS (5p / minute and 4p / SMS thereafter)',
                    'price' => 15.99,
                    'discount' => '',
                ],
                [
                    'option title' => 'Standard: 1GB Data - 12 Months',
                    'description' => 'Up to 1 GB data per month including 35 SMS (5p / MB data and 4p / SMS thereafter)',
                    'price' => 9.99,
                    'discount' => '',
                ],
                [
                    'option title' => 'Basic: 500MB Data - 12 Months',
                    'description' => 'Up to 500MB of data per month including 20 SMS (5p / MB data and 4p / SMS thereafter)',
                    'price' => 5.99,
                    'discount' => '',
                ],
            ],
            $result
        );
    }
}