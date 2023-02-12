<?php

namespace App\WebScraper\Source;

use App\WebScraper\Exception\SourceNotFoundException;

class SourceService implements SourceServiceInterface
{
    public function __construct(
        /** @var SourceInterface[] $sources */
        private readonly iterable $sources,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getSourceUsingContext(array $context): SourceInterface
    {
        if (empty($context['source'])) {
            throw new SourceNotFoundException(
                'Unable to find source as source not provided within context'
            );
        }

        foreach ($this->sources as $source) {
            if ($context['source'] === $source->getIdentifier()) {
                return $source;
            }
        }

        throw new SourceNotFoundException(
            sprintf('Unable to find source using option %s', $context['source'])
        );
    }
}