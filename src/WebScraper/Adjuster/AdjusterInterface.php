<?php

namespace App\WebScraper\Adjuster;

interface AdjusterInterface
{
    /**
     * Evaluates if the adjust method should be run.
     */
    public function supports(array $data, array $context): bool;

    /**
     * Adjusts the $data passed into the method.
     */
    public function adjust(array $data, array $context): array;
}