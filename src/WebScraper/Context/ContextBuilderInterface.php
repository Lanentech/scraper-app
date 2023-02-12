<?php

namespace App\WebScraper\Context;

use App\WebScraper\Exception\InvalidScrapeOptionsProvidedException;
use Symfony\Component\Console\Input\InputInterface;

interface ContextBuilderInterface
{
    /**
     * Builds a context array using the Symfony\Component\Console\Input\InputInterface.
     *
     * @throws InvalidScrapeOptionsProvidedException
     */
    public function build(InputInterface $input): array;
}