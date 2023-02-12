<?php

namespace App\WebScraper;

use App\WebScraper\Exception\FormatterNotFoundException;
use App\WebScraper\Exception\InvalidScrapeOptionsProvidedException;
use Symfony\Component\Console\Input\InputInterface;

interface ScraperServiceInterface
{
    /**
     * Performs a Web Scrape.
     *
     * @throws FormatterNotFoundException
     * @throws InvalidScrapeOptionsProvidedException
     */
    public function scrape(InputInterface $input): string;
}