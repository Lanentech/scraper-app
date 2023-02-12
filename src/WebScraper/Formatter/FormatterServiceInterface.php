<?php

namespace App\WebScraper\Formatter;

use App\WebScraper\Exception\FormatterNotFoundException;

interface FormatterServiceInterface
{
    /**
     * Formats data.
     *
     * @throws FormatterNotFoundException
     */
    public function formatData(array $data, array $context): string;
}