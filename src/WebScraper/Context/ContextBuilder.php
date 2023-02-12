<?php

namespace App\WebScraper\Context;

use App\WebScraper\Exception\InvalidScrapeOptionsProvidedException;
use App\WebScraper\Exception\SourceNotFoundException;
use App\WebScraper\Source\SourceServiceInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;

class ContextBuilder implements ContextBuilderInterface
{
    public function __construct(
        private readonly SourceServiceInterface $sourceService,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function build(InputInterface $input): array
    {
        try {
            $context = [
                'format' => $input->getOption('format'),
                'price-sort' => $input->getOption('price-sort'),
                'source' => $input->getOption('source'),
            ];

            return array_merge($context, [
                'sourceInstance' => $this->sourceService->getSourceUsingContext($context),
            ]);
        } catch (InvalidArgumentException|SourceNotFoundException $exception) {
            throw new InvalidScrapeOptionsProvidedException(
                sprintf('Cannot build context (Error: %s)', $exception->getMessage())
            );
        }
    }
}