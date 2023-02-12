<?php

namespace App\WebScraper\Formatter;

use App\WebScraper\Exception\FormatterNotFoundException;

class FormatterService implements FormatterServiceInterface
{
    /**
     * @param FormatterInterface[] $formatters
     */
    public function __construct(
        private readonly iterable $formatters,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function formatData(array $data, array $context): string
    {
        $format = $context['format'] ?? '';

        if (empty($format)) {
            throw new FormatterNotFoundException('Format option not provided.');
        }

        foreach ($this->formatters as $formatter) {
            if ($formatter->supports($data, $context)) {
                return $formatter->format($data, $context);
            }
        }

        throw new FormatterNotFoundException(
            sprintf('No Formatter found that supports the option "%s".', $format)
        );
    }
}