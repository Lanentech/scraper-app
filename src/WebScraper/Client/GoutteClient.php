<?php

namespace App\WebScraper\Client;

use App\WebScraper\Source\SourceInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

class GoutteClient implements ClientInterface
{
    public function __construct(
        private readonly HttpBrowser $client,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function performScrape(SourceInterface $source): array
    {
        $data = [];

        $crawler = $this->client->request('GET', $source->getUrl());
        $crawler->filter($source->getProductsSelector())->each(
            function (Crawler $node) use (&$data, $source) {
                /** @var SourceInterface $source */
                $data[] = [
                    'option title' => $node->filter($source->getOptionTitleSelector())->text(''),
                    'description' => $this->adjustDescription(
                        $node->filter($source->getDescriptionSelector())->html('')
                    ),
                    'price' => $this->adjustPrice(
                        $source,
                        $node->filter($source->getPriceSelector())->text('')
                    ),
                    'discount' => $node->filter('div.package-price p')->text(''),
                ];
            }
        );

        return $data;
    }

    private function adjustDescription(string $description): string
    {
        return trim(preg_replace(['/<br>/', '/\s+/'], ' ', $description));
    }

    private function adjustPrice(SourceInterface $source, string $price): float
    {
        return (float) str_replace($source->getPriceSymbolPrefix(), '', $price);
    }
}