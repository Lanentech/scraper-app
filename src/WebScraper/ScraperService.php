<?php

namespace App\WebScraper;

use App\WebScraper\Adjuster\AdjusterServiceInterface;
use App\WebScraper\Client\ClientInterface;
use App\WebScraper\Context\ContextBuilderInterface;
use App\WebScraper\Formatter\FormatterServiceInterface;
use App\WebScraper\Sorter\SortServiceInterface;
use Symfony\Component\Console\Input\InputInterface;

class ScraperService implements ScraperServiceInterface
{
    public function __construct(
        private readonly ClientInterface $client,
        private readonly ContextBuilderInterface $contextBuilder,
        private readonly AdjusterServiceInterface $adjusterService,
        private readonly FormatterServiceInterface $formatterService,
        private readonly SortServiceInterface $sortService,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function scrape(InputInterface $input): string
    {
        $context = $this->contextBuilder->build($input);

        $data = $this->client->performScrape($context['sourceInstance']);
        $data = $this->sortService->sortData($data, $context);
        $data = $this->adjusterService->adjustData($data, $context);

        return $this->formatterService->formatData($data, $context);
    }
}