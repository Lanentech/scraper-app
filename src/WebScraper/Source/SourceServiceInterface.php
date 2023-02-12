<?php

namespace App\WebScraper\Source;

use App\WebScraper\Exception\SourceNotFoundException;

interface SourceServiceInterface
{
    /**
     * Returns Source using Context provided.
     *
     * @throws SourceNotFoundException
     */
    public function getSourceUsingContext(array $context): SourceInterface;
}