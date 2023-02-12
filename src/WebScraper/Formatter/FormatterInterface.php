<?php

namespace App\WebScraper\Formatter;

interface FormatterInterface
{
    public const OPTION_JSON = 'json';

    /**
     * Evaluates if the format method should be run.
     */
    public function supports(array $data, array $context): bool;

    /**
     * Formats and returns the $data.
     */
    public function format(array $data, array $context): string;
}