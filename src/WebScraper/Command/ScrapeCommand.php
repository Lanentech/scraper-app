<?php

namespace App\WebScraper\Command;

use App\WebScraper\Formatter\FormatterInterface;
use App\WebScraper\ScraperServiceInterface;
use App\WebScraper\Sorter\PriceSorter;
use App\WebScraper\Source\WirelessLogicSource;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:scrape',
    description: 'Scrapes the given website and returns product data as JSON (See --help for more options)',
)]
class ScrapeCommand extends Command
{
    public function __construct(
        private readonly ScraperServiceInterface $service,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('format', null, InputOption::VALUE_OPTIONAL, 'Sets the output format', FormatterInterface::OPTION_JSON)
            ->addOption('price-sort', null, InputOption::VALUE_OPTIONAL, 'Sets how the prices should be sorted', PriceSorter::SORT_DESCENDING)
            ->addOption('source', null, InputOption::VALUE_OPTIONAL, 'Sets the source to use via a sources identifier', WirelessLogicSource::IDENTIFIER)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $output->writeln(
                sprintf('<info>%s</info>', $this->service->scrape($input))
            );
        } catch (\Throwable $exception) {
            $output->writeln(
                sprintf(
                    '<error>Scrape Failed.%s%s</error>',
                    PHP_EOL,
                    $exception->getMessage()
                )
            );
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
