<?php

namespace App\Tests\Unit\WebScraper\Formatter;

use App\Tests\Traits\MockContextTrait;
use App\Tests\Traits\MockDataTrait;
use App\WebScraper\Exception\FormatterNotFoundException;
use App\WebScraper\Formatter\FormatterService;
use App\WebScraper\Formatter\JsonFormatter;
use PHPUnit\Framework\TestCase;

class FormatterServiceTest extends TestCase
{
    use MockContextTrait;
    use MockDataTrait;

    public function testFormatMethodWithContextMissingFormat()
    {
        $this->expectException(FormatterNotFoundException::class);
        $this->expectErrorMessage('Format option not provided.');

        $context = $this->getMockContextArray();
        unset($context['format']);

        $sut = new FormatterService([]);
        $sut->formatData(
            $this->getMockDataArray(),
            $context
        );
    }

    public function testFormatMethodWithNoFormattersInjectedIntoConstruct()
    {
        $this->expectException(FormatterNotFoundException::class);
        $this->expectErrorMessage('No Formatter found that supports the option "json".');

        $sut = new FormatterService([]);

        $sut->formatData(
            $this->getMockDataArray(),
            $this->getMockContextArray()
        );
    }

    public function testFormatMethodWithFormattersInjectedIntoConstruct()
    {
        $sut = new FormatterService([
            new JsonFormatter(),
        ]);

        $result = $sut->formatData(
            $this->getMockDataArray(),
            $this->getMockContextArray()
        );

        $this->assertEquals(
            str_replace("\r\n", "\n", file_get_contents('tests/Expected/Response/WebScraper/Json/unaltered-mock-data-as-json.json')),
            $result
        );
    }
}