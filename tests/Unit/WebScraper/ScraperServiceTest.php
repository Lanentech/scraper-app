<?php

namespace App\Tests\Unit\WebScraper;

use App\Tests\Traits\MockContextTrait;
use App\Tests\Traits\MockDataTrait;
use App\WebScraper\Adjuster\AdjusterServiceInterface;
use App\WebScraper\Client\ClientInterface;
use App\WebScraper\Context\ContextBuilderInterface;
use App\WebScraper\Exception\FormatterNotFoundException;
use App\WebScraper\Exception\InvalidScrapeOptionsProvidedException;
use App\WebScraper\Formatter\FormatterServiceInterface;
use App\WebScraper\ScraperService;
use App\WebScraper\Sorter\SortServiceInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Input\InputInterface;

class ScraperServiceTest extends TestCase
{
    use MockContextTrait;
    use MockDataTrait;
    use ProphecyTrait;

    private ClientInterface|ObjectProphecy $client;
    private ContextBuilderInterface|ObjectProphecy $contextBuilder;
    private AdjusterServiceInterface|ObjectProphecy $adjusterService;
    private FormatterServiceInterface|ObjectProphecy $formatterService;
    private SortServiceInterface|ObjectProphecy $sortService;
    private ScraperService $sut;

    public function setUp(): void
    {
        $this->client = $this->prophesize(ClientInterface::class);
        $this->contextBuilder = $this->prophesize(ContextBuilderInterface::class);
        $this->adjusterService = $this->prophesize(AdjusterServiceInterface::class);
        $this->formatterService = $this->prophesize(FormatterServiceInterface::class);
        $this->sortService = $this->prophesize(SortServiceInterface::class);

        $this->sut = new ScraperService(
            $this->client->reveal(),
            $this->contextBuilder->reveal(),
            $this->adjusterService->reveal(),
            $this->formatterService->reveal(),
            $this->sortService->reveal(),
        );
    }

    public function testScrapeWhenContextBuilderThrowsAnException()
    {
        $this->expectException(InvalidScrapeOptionsProvidedException::class);

        $input = $this->prophesize(InputInterface::class);

        $this->contextBuilder->build($input->reveal())->willThrow(InvalidScrapeOptionsProvidedException::class);
        $this->client->performScrape(Argument::any())->shouldNotBeCalled();
        $this->sortService->sortData(Argument::any(), Argument::any())->shouldNotBeCalled();
        $this->adjusterService->adjustData(Argument::any(), Argument::any())->shouldNotBeCalled();
        $this->formatterService->formatData(Argument::any(), Argument::any())->shouldNotBeCalled();

        $this->sut->scrape($input->reveal());
    }

    public function testScrapeWhenFormatterServiceThrowsAnException()
    {
        $this->expectException(FormatterNotFoundException::class);

        $context = $this->getMockContextArray();
        $data = $this->getMockDataArray();
        $input = $this->prophesize(InputInterface::class);

        $this->contextBuilder->build($input->reveal())->shouldBeCalled()->willReturn($context);
        $this->client->performScrape(Argument::any())->shouldBeCalled()->willReturn($data);
        $this->sortService->sortData($data, $context)->shouldBeCalled()->willReturn($data);
        $this->adjusterService->adjustData($data, $context)->shouldBeCalled()->willReturn($data);
        $this->formatterService->formatData($data, $context)
            ->willThrow(FormatterNotFoundException::class)
        ;

        $this->sut->scrape($input->reveal());
    }

    public function testScrapeWhenNoExceptionsThrown()
    {
        $context = $this->getMockContextArray();
        $data = $this->getMockDataArray();
        $input = $this->prophesize(InputInterface::class);
        $expectedJson = file_get_contents('tests/Expected/Response/WebScraper/Json/unaltered-mock-data-as-json.json');

        $this->contextBuilder->build($input->reveal())->shouldBeCalled()->willReturn($context);
        $this->client->performScrape(Argument::any())->shouldBeCalled()->willReturn($data);
        $this->sortService->sortData($data, $context)->shouldBeCalled()->willReturn($data);
        $this->adjusterService->adjustData($data, $context)->shouldBeCalled()->willReturn($data);
        $this->formatterService->formatData($data, $context)->shouldBeCalled()->willReturn($expectedJson);

        $result = $this->sut->scrape($input->reveal());

        $this->assertEquals($expectedJson, $result);
    }
}