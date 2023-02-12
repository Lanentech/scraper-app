<?php

namespace App\WebScraper\Formatter;

class JsonFormatter implements FormatterInterface
{
    /**
     * @inheritDoc
     */
    public function supports(array $data, array $context): bool
    {
        return ($context['format'] ?? '') === FormatterInterface::OPTION_JSON;
    }

    /**
     * @inheritDoc
     */
    public function format(array $data, array $context): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    }
}