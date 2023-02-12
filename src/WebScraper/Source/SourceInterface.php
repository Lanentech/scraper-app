<?php

namespace App\WebScraper\Source;

interface SourceInterface
{
    /**
     * Returns an identifier, used by the Scrape command when determining which source to use.
     */
    public function getIdentifier(): string;

    /**
     * Returns Web URL to be scraped.
     */
    public function getUrl(): string;

    /**
     * Returns Products Selector for Crawler to filter on.
     */
    public function getProductsSelector(): string;

    /**
     * Returns Option Title Selector for Crawler to filter on.
     */
    public function getOptionTitleSelector(): string;

    /**
     * Returns Description Selector for Crawler to filter on.
     */
    public function getDescriptionSelector(): string;

    /**
     * Returns Price Selector for Crawler to filter on.
     */
    public function getPriceSelector(): string;

    /**
     * Returns Discount Selector for Crawler to filter on.
     */
    public function getDiscountSelector(): string;

    /**
     * Returns Price Symbol that prefixed price value.
     */
    public function getPriceSymbolPrefix(): string;
}