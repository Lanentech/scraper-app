<?php

namespace App\WebScraper\Source;

class WirelessLogicSource implements SourceInterface
{
    public const IDENTIFIER = 'wireless-logic';

    /**
     * @inheritDoc
     */
    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return 'https://wltest.dns-systems.net';
    }

    /**
     * @inheritDoc
     */
    public function getProductsSelector(): string
    {
        return '#subscriptions > div > div.pricing-table > div > div > div.package';
    }

    /**
     * @inheritDoc
     */
    public function getOptionTitleSelector(): string
    {
        return 'div.header';
    }

    /**
     * @inheritDoc
     */
    public function getDescriptionSelector(): string
    {
        return 'div.package-description';
    }

    /**
     * @inheritDoc
     */
    public function getPriceSelector(): string
    {
        return 'div.package-price span.price-big';
    }

    /**
     * @inheritDoc
     */
    public function getDiscountSelector(): string
    {
        return 'div.package-price p';
    }

    /**
     * @inheritDoc
     */
    public function getPriceSymbolPrefix(): string
    {
        return '£';
    }
}