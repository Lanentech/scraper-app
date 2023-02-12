<?php

namespace App\Tests\Functional\WebScraper\Client;

use App\WebScraper\Client\GoutteClient;
use App\WebScraper\Source\WirelessLogicSource;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class GoutteClientTest extends WebTestCase
{
    public function testPerformScrapeWhenCrawlReturnsBlankString()
    {
        $mockResponse = new MockResponse('');
        $mockHttpClient = new MockHttpClient($mockResponse);
        $mockHttpBrowser = new HttpBrowser($mockHttpClient);
        $sut = new GoutteClient($mockHttpBrowser);

        $result = $sut->performScrape(new WirelessLogicSource());

        $this->assertEmpty($result);
    }

    public function testPerformScrapeWhenCrawlReturnsPartialSubscriptionDataSet()
    {
        $mockResponse = new MockResponse(
            file_get_contents('tests/Expected/Response/WebScraper/Xml/partials-results-from-web-scrape.xml')
        );
        $mockHttpClient = new MockHttpClient($mockResponse);
        $mockHttpBrowser = new HttpBrowser($mockHttpClient);
        $sut = new GoutteClient($mockHttpBrowser);

        $result = $sut->performScrape(new WirelessLogicSource());

        $this->assertEquals(
            [
                [
                    "option title" => "Optimum: 2 GB Data - 12 Months",
                    "description" => "2GB data per month including 40 SMS (5p / minute and 4p / SMS thereafter)",
                    "price" => 15.99,
                    "discount" => "",
                ],
                [
                    "option title" => "Basic: 6GB Data - 1 Year",
                    "description" => "Up to 6GB of data per year including 240 SMS (5p / MB data and 4p / SMS thereafter)",
                    "price" => 66.0,
                    "discount" => "Save £5.86 on the monthly price",
                ],
            ],
            $result
        );
    }

    public function testPerformScrapeWhenCrawlReturnsFullSubscriptionDataSet()
    {
        $mockResponse = new MockResponse(
            file_get_contents('tests/Expected/Response/WebScraper/Xml/full-web-scrape.xml')
        );
        $mockHttpClient = new MockHttpClient($mockResponse);
        $mockHttpBrowser = new HttpBrowser($mockHttpClient);
        $sut = new GoutteClient($mockHttpBrowser);

        $result = $sut->performScrape(new WirelessLogicSource());

        $this->assertEquals(
            [
                [
                    "option title" => "Basic: 500MB Data - 12 Months",
                    "description" => "Up to 500MB of data per month including 20 SMS (5p / MB data and 4p / SMS thereafter)",
                    "price" => 5.99,
                    "discount" => "",
                ],
                [
                    "option title" => "Standard: 1GB Data - 12 Months",
                    "description" => "Up to 1 GB data per month including 35 SMS (5p / MB data and 4p / SMS thereafter)",
                    "price" => 9.99,
                    "discount" => "",
                ],
                [
                    "option title" => "Optimum: 2 GB Data - 12 Months",
                    "description" => "2GB data per month including 40 SMS (5p / minute and 4p / SMS thereafter)",
                    "price" => 15.99,
                    "discount" => "",
                ],
                [
                    "option title" => "Basic: 6GB Data - 1 Year",
                    "description" => "Up to 6GB of data per year including 240 SMS (5p / MB data and 4p / SMS thereafter)",
                    "price" => 66.0,
                    "discount" => "Save £5.86 on the monthly price",
                ],
                [
                    "option title" => "Standard: 12GB Data - 1 Year",
                    "description" => "Up to 12GB of data per year including 420 SMS (5p / MB data and 4p / SMS thereafter)",
                    "price" => 108.0,
                    "discount" => "Save £11.90 on the monthly price",
                ],
                [
                    "option title" => "Optimum: 24GB Data - 1 Year",
                    "description" => "Up to 12GB of data per year including 480 SMS (5p / MB data and 4p / SMS thereafter)",
                    "price" => 174.0,
                    "discount" => "Save £17.90 on the monthly price",
                ],
            ],
            $result
        );
    }
}