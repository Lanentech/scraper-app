<?php

namespace App\Tests\Unit\WebScraper\Adjuster;

use App\Tests\Traits\MockContextTrait;
use App\Tests\Traits\MockDataTrait;
use App\WebScraper\Adjuster\PriceAdjuster;
use PHPUnit\Framework\TestCase;

class PriceAdjusterTest extends TestCase
{
    use MockContextTrait;
    use MockDataTrait;

    private PriceAdjuster $sut;

    public function setUp(): void
    {
        $this->sut = new PriceAdjuster();
    }

    public function supportsMethodReturnsFalseDataProvider(): iterable
    {
        yield 'empty data and empty context' => [[], []];

        $data = $this->getMockDataArray();
        foreach ($data as &$datum) {
            unset($datum['price']);
        }
        yield 'data array missing price key' => [
            $data,
            $this->getMockContextArray(),
        ];

        $data = $this->getMockDataArray();
        foreach ($data as &$datum) {
            $datum['price'] = 'three';
        }
        yield 'data array price values are not numeric' => [
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

    public function testAdjustMethod()
    {
        $context = $this->getMockContextArray();
        $data = $this->getMockDataArray();

        $result = $this->sut->adjust($data, $context);

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