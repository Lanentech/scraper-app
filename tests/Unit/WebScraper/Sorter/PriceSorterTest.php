<?php

namespace App\Tests\Unit\WebScraper\Sorter;

use App\Tests\Traits\MockContextTrait;
use App\Tests\Traits\MockDataTrait;
use App\WebScraper\Sorter\PriceSorter;
use PHPUnit\Framework\TestCase;

class PriceSorterTest extends TestCase
{
    use MockContextTrait;
    use MockDataTrait;

    private PriceSorter $sut;

    public function setUp(): void
    {
        $this->sut = new PriceSorter();
    }

    public function supportsMethodReturnsFalseDataProvider(): iterable
    {
        yield 'empty data and empty context' => [[], []];

        $context = $this->getMockContextArray();
        unset($context['price-sort']);
        yield 'context missing price-sort option' => [
            $this->getMockDataArray(),
            $context,
        ];

        $context = $this->getMockContextArray(priceSort: 'invalid');
        yield 'context price-sort invalid' => [
            $this->getMockDataArray(),
            $context,
        ];

        $data = $this->getMockDataArray();
        foreach ($data as &$datum) {
            unset($datum['price']);
        }
        yield 'data array missing price key' => [
            $data,
            $this->getMockContextArray(),
        ];
    }

    /**
     * @dataProvider supportsMethodReturnsFalseDataProvider
     */
    public function testSupportsMethodReturnsFalse(array $data, array $context)
    {
        $result = $this->sut->supports($data, $context);

        $this->assertFalse($result);
    }

    public function testSupportsMethodReturnsTrue()
    {
        $context = $this->getMockContextArray();
        $data = $this->getMockDataArray();

        $result = $this->sut->supports($data, $context);

        $this->assertTrue($result);
    }

    public function testSortMethodSortsPricesDescending()
    {
        $result = $this->sut->sort(
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

    public function testSortMethodSortsPricesAscending()
    {
        $result = $this->sut->sort(
            $this->getMockDataArray(),
            $this->getMockContextArray(priceSort: PriceSorter::SORT_ASCENDING)
        );

        $this->assertEquals(
            [
                [
                    'option title' => 'Basic: 500MB Data - 12 Months',
                    'description' => 'Up to 500MB of data per month including 20 SMS (5p / MB data and 4p / SMS thereafter)',
                    'price' => 5.99,
                    'discount' => '',
                ],
                [
                    'option title' => 'Standard: 1GB Data - 12 Months',
                    'description' => 'Up to 1 GB data per month including 35 SMS (5p / MB data and 4p / SMS thereafter)',
                    'price' => 9.99,
                    'discount' => '',
                ],
                [
                    'option title' => 'Optimum: 2 GB Data - 12 Months',
                    'description' => '2GB data per month including 40 SMS (5p / minute and 4p / SMS thereafter)',
                    'price' => 15.99,
                    'discount' => '',
                ],
            ],
            $result
        );
    }
}