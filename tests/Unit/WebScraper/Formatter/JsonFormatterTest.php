<?php

namespace App\Tests\Unit\WebScraper\Formatter;

use App\Tests\Traits\MockContextTrait;
use App\Tests\Traits\MockDataTrait;
use App\WebScraper\Formatter\JsonFormatter;
use PHPUnit\Framework\TestCase;

class JsonFormatterTest extends TestCase
{
    use MockContextTrait;
    use MockDataTrait;

    private JsonFormatter $sut;

    public function setUp(): void
    {
        $this->sut = new JsonFormatter();
    }

    public function supportsMethodReturnsFalseDataProvider(): iterable
    {
        yield 'empty data and empty context' => [[], []];

        $context = $this->getMockContextArray();
        unset($context['format']);
        yield 'context missing format option' => [
            $this->getMockDataArray(),
            $context,
        ];

        $context = $this->getMockContextArray(format: 'xml');
        yield 'context format value mismatch' => [
            $this->getMockDataArray(),
            $context,
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

    public function testFormatMethod()
    {
        $result = $this->sut->format(
            $this->getMockDataArray(),
            $this->getMockContextArray()
        );

        $this->assertEquals(
            str_replace("\r\n", "\n", file_get_contents('tests/Expected/Response/WebScraper/Json/unaltered-mock-data-as-json.json')),
            $result
        );
    }
}