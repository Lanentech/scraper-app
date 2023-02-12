<?php

namespace App\Tests\Unit\WebScraper\Command;

use App\WebScraper\Command\ScrapeCommand;
use App\WebScraper\Exception\FormatterNotFoundException;
use App\WebScraper\ScraperServiceInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ScrapeCommandTest extends TestCase
{
    use ProphecyTrait;

    public function testCommandFailsWhenScraperServiceThrowsException()
    {
        $scraperService = $this->prophesize(ScraperServiceInterface::class);
        $scraperService->scrape(Argument::any())->willThrow(FormatterNotFoundException::class);
        $command = new ScrapeCommand($scraperService->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
    }

    public function testCommandPasses()
    {
        $scraperService = $this->prophesize(ScraperServiceInterface::class);
        $scraperService->scrape(Argument::any())->willReturn(
            file_get_contents('tests/Expected/Response/WebScraper/Json/unaltered-mock-data-as-json.json')
        );
        $command = new ScrapeCommand($scraperService->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
    }
}