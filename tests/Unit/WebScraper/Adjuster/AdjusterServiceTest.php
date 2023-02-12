<?php

namespace App\Tests\Unit\WebScraper\Adjuster;

use App\Tests\Traits\MockContextTrait;
use App\Tests\Traits\MockDataTrait;
use App\WebScraper\Adjuster\AdjusterService;
use App\WebScraper\Adjuster\PriceAdjuster;
use PHPUnit\Framework\TestCase;

class AdjusterServiceTest extends TestCase
{
    use MockContextTrait;
    use MockDataTrait;

    public function testAdjustDataMethodWithNoSortersInjectedIntoConstruct()
    {
        $data = $this->getMockDataArray();
        $sut = new AdjusterService([]);

        $result = $sut->adjustData(
            $data,
            $this->getMockContextArray()
        );

        $this->assertEquals($data, $result);
    }

    public function testAdjustDataMethodWithSortersInjectedIntoConstruct()
    {
        $sut = new AdjusterService([
            new PriceAdjuster(),
        ]);

        $result = $sut->adjustData(
            $this->getMockDataArray(),
            $this->getMockContextArray()
        );

        $this->assertEquals(
            [
                [
                    'option title' => 'Basic: 500MB Data - 12 Months',
                    'description' => 'Up to 500MB of data per month including 20 SMS (5p / MB data and 4p / SMS thereafter)',
                    'price' => '£5.99',
                    'discount' => '',
                ],
                [
                    'option title' => 'Optimum: 2 GB Data - 12 Months',
                    'description' => '2GB data per month including 40 SMS (5p / minute and 4p / SMS thereafter)',
                    'price' => '£15.99',
                    'discount' => '',
                ],
                [
                    'option title' => 'Standard: 1GB Data - 12 Months',
                    'description' => 'Up to 1 GB data per month including 35 SMS (5p / MB data and 4p / SMS thereafter)',
                    'price' => '£9.99',
                    'discount' => '',
                ],
            ],
            $result
        );
    }
}