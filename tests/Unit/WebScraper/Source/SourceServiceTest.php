<?php

namespace App\Tests\Unit\WebScraper\Source;

use App\WebScraper\Exception\SourceNotFoundException;
use App\WebScraper\Source\SourceService;
use App\WebScraper\Source\WirelessLogicSource;
use PHPUnit\Framework\TestCase;

class SourceServiceTest extends TestCase
{
    private SourceService $sut;

    public function setUp(): void
    {
        $this->sut = new SourceService([
            new WirelessLogicSource(),
        ]);
    }

    public function testGetSourceUsingContextThrowsExceptionWhenSourceKeyNotFoundInContext()
    {
        $this->expectException(SourceNotFoundException::class);
        $this->expectErrorMessage('Unable to find source as source not provided within context');

        $this->sut->getSourceUsingContext([]);
    }

    public function testGetSourceUsingContextReturnsSource()
    {
        $context = [
            'source' => WirelessLogicSource::IDENTIFIER,
        ];

        $result = $this->sut->getSourceUsingContext($context);

        $this->assertInstanceOf(WirelessLogicSource::class, $result);
    }

    public function testGetSourceUsingContextThrowsExceptionWhenSourceKeyDoesNotMatchSourceService()
    {
        $class = 'App\WebScraper\Source\InvalidSource';

        $this->expectException(SourceNotFoundException::class);
        $this->expectErrorMessage('Unable to find source using option ' . $class);

        $context = [
            'source' => $class,
        ];

        $this->sut->getSourceUsingContext($context);
    }
}