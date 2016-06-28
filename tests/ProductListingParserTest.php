<?php
namespace Priceminister\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Priceminister\PriceministerClient;
use Priceminister\ProductListing;

class ProductListingParserTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $priceministerClient;

    public function setUp()
    {
        $this->priceministerClient = new PriceministerClient('toto', 'p4ssw0rd');
    }

    public function testGetAttributesOfAValidResponseParameters()
    {
        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws.xml');

        $response = new Response(200, [], $fixtures);
        $mock = new MockHandler([$response]);

        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 121518297);
        $result = $productListing->request();

        $this->assertEquals('73.49', $result->getBestPrice());
        $this->assertEquals('69.99', $result->getBestPrice(false));

        $this->assertEquals('Star Wars X-Wing Rogue Squadron Tome 9 - Dette De Sang', $result->getHeadline());
        $this->assertEquals('121518297', $result->getId());

        $products[] = [
            'id' => 121518297,
            'values' => [
                'name' => 'Star Wars X-Wing Rogue Squadron Tome 9 - Dette De Sang',
                'image' => 'http://pmcdn.priceminister.com/photo/895559632_ML.jpg',
                'breadcrumb' => 'Livres > BD et livres d\'humour > Comics',
                'caption' => 'Steve Crespo',
                'topic' => 'Livre',
                'offers' => 1,
                'bestprice' => 69
            ]
        ];

        $this->assertEquals($products, $result->getProducts());

    }

    public function testGetPriceOfAValidResponseWithNotOffersParameters()
    {
        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws_no_offers.xml');

        $response = new Response(200, [], $fixtures);
        $mock = new MockHandler([$response]);

        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 'World of warcraft tome 14');
        $result = $productListing->request();

        $this->assertEquals(0, $result->getBestPrice());
    }

    public function testGetHeadlineOfAValidResponseParameters()
    {
        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws.xml');

        $response = new Response(200, [], $fixtures);
        $mock = new MockHandler([$response]);

        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 121518297);
        $result = $productListing->request();

        $this->assertEquals('Star Wars X-Wing Rogue Squadron Tome 9 - Dette De Sang', $result->getHeadline());
    }

    public function testXmlValidOfAValidResponseWithNotProductsParameters()
    {
        $this->expectException(\Exception::class);
        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws_empty.xml');

        $response = new Response(200, [], $fixtures);
        $mock = new MockHandler([$response]);

        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 'World of warcraft tome 14');
        $result = $productListing->request();

        $this->assertEquals(0, $result->getBestPrice());
    }

    public function testGetPageNumberOfAValidResponse()
    {
        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws.xml');

        $response = new Response(200, [], $fixtures);
        $mock = new MockHandler([$response]);

        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 121518297);
        $result = $productListing->request();

        $this->assertEquals(1, $result->getPageNumber());
    }

    public function testNumberOfResultsOfAValidResponse()
    {
        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws.xml');

        $response = new Response(200, [], $fixtures);
        $mock = new MockHandler([$response]);

        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 121518297);
        $result = $productListing->request();

        $this->assertEquals(1, $result->getTotalResultCount());
    }
}

