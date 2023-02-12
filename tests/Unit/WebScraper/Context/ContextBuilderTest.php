<?php

namespace App\Tests\Unit\WebScraper\Context;

use App\WebScraper\Context\ContextBuilder;
use App\WebScraper\Exception\InvalidScrapeOptionsProvidedException;
use App\WebScraper\Exception\SourceNotFoundException;
use App\WebScraper\Formatter\JsonFormatter;
use App\WebScraper\Sorter\PriceSorter;
use App\WebScraper\Source\SourceServiceInterface;
use App\WebScraper\Source\WirelessLogicSource;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;

class ContextBuilderTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy $sourceService;
    private ContextBuilder $sut;

    public function setUp(): void
    {
        $this->sourceService = $this->prophesize(SourceServiceInterface::Class);

        $this->sut = new ContextBuilder(
            $this->sourceService->reveal(),
        );
    }

    public function testBuildMethodWhenInputOptionFormatDoesNotExistThrowsException()
    {
        $this->expectException(InvalidScrapeOptionsProvidedException::Class);

        $input = $this->prophesize(InputInterface::class);
        $input->getOption('format')->willThrow(InvalidArgumentException::class);

        $this->sut->build($input->reveal());
    }

    public function testBuildMethodWhenSourceInstanceCannotFoundThrowsException()
    {
        $this->expectException(InvalidScrapeOptionsProvidedException::Class);

        $input = $this->prophesize(InputInterface::class);
        $input->getOption('format')->willReturn(JsonFormatter::OPTION_JSON);
        $input->getOption('price-sort')->willReturn(PriceSorter::SORT_DESCENDING);
        $input->getOption('source')->willReturn('invalid-source');

        $this->sourceService->getSourceUsingContext(Argument::any())->willThrow(SourceNotFoundException::class);

        $this->sut->build($input->reveal());
    }

    public function testBuildMethodWhenSourceFound()
    {
        $source = new WirelessLogicSource();
        $input = $this->prophesize(InputInterface::class);
        $input->getOption('format')->willReturn(JsonFormatter::OPTION_JSON);
        $input->getOption('price-sort')->willReturn(PriceSorter::SORT_DESCENDING);
        $input->getOption('source')->willReturn(WirelessLogicSource::IDENTIFIER);

        $this->sourceService
            ->getSourceUsingContext([
                'format' => JsonFormatter::OPTION_JSON,
                'price-sort' => PriceSorter::SORT_DESCENDING,
                'source' => WirelessLogicSource::IDENTIFIER,
            ])
            ->willReturn($source)
        ;

        $result = $this->sut->build($input->reveal());

        $this->assertEquals(
            [
                'format' => JsonFormatter::OPTION_JSON,
                'price-sort' => PriceSorter::SORT_DESCENDING,
                'source' => WirelessLogicSource::IDENTIFIER,
                'sourceInstance' => $source,
            ],
            $result
        );
    }
}