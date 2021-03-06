<?php

namespace Priceminister\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Priceminister\PriceministerClient;
use Priceminister\ProductListing;
use Priceminister\ProductListingRequest;

class ProductListingParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var PriceministerClient
     */
    private $priceministerClient;

    public function setUp()
    {
        $this->priceministerClient = new PriceministerClient('toto', 'p4ssw0rd');
    }

    /**
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetAttributesOfAValidResponseParameters()
    {
        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws.xml');

        $response = new Response(200, [], $fixtures);
        $mock     = new MockHandler([$response]);

        $handler      = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 121518297);
        $productListing->validParameters();

        $plRequest = new ProductListingRequest($productListing);
        $result    = $plRequest->fetch();

        $this->assertEquals(73.49, $result->getBestPrice());
        $this->assertEquals(69.99, $result->getBestPriceWithoutShippingCost());
        $this->assertEquals(3.50, $result->getShippingCost());

        $this->assertEquals('Star Wars X-Wing Rogue Squadron Tome 9 - Dette De Sang', $result->getHeadline());
        $this->assertEquals('121518297', $result->getId());

        $products[] = [
            'id'     => 121518297,
            'values' => [
                'name'       => 'Star Wars X-Wing Rogue Squadron Tome 9 - Dette De Sang',
                'image'      => 'http://pmcdn.priceminister.com/photo/895559632_ML.jpg',
                'breadcrumb' => 'Livres > BD et livres d\'humour > Comics',
                'caption'    => 'Steve Crespo',
                'topic'      => 'Livre',
                'offers'     => 1,
                'bestprice'  => 69,
                'url'        => 'http://www.priceminister.com/offer/buy/121518297/star-wars-x-wing-rogue-squadron-tome-9-dette-de-sang-de-steve-crespo-livre.html',
                'barcode'    => '9782756025544'
            ]
        ];

        $this->assertEquals($products, $result->getProducts());

    }

    public function testGetPriceOfAValidResponseWithNotOffersParameters()
    {
        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws_no_offers.xml');

        $response = new Response(200, [], $fixtures);
        $mock     = new MockHandler([$response]);

        $handler      = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 'World of warcraft tome 14');
        $productListing->validParameters();

        $plRequest = new ProductListingRequest($productListing);
        $result    = $plRequest->fetch();

        $this->assertEquals(0, $result->getBestPrice());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetHeadlineOfAValidResponseParameters()
    {
        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws.xml');

        $response = new Response(200, [], $fixtures);
        $mock     = new MockHandler([$response]);

        $handler      = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 121518297);
        $productListing->validParameters();

        $plRequest = new ProductListingRequest($productListing);
        $result    = $plRequest->fetch();

        $this->assertEquals('Star Wars X-Wing Rogue Squadron Tome 9 - Dette De Sang', $result->getHeadline());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testXmlValidOfAValidResponseWithNotProductsParameters()
    {
        $this->expectException(\Exception::class);
        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws_empty.xml');

        $response = new Response(200, [], $fixtures);
        $mock     = new MockHandler([$response]);

        $handler      = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 'World of warcraft tome 14');
        $productListing->validParameters();

        $plRequest = new ProductListingRequest($productListing);
        $result    = $plRequest->fetch();

        $this->assertEquals(0, $result->getBestPrice());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetPageNumberOfAValidResponse()
    {
        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws.xml');

        $response = new Response(200, [], $fixtures);
        $mock     = new MockHandler([$response]);

        $handler      = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 121518297);
        $productListing->validParameters();

        $plRequest = new ProductListingRequest($productListing);
        $result    = $plRequest->fetch();

        $this->assertEquals(1, $result->getPageNumber());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testNumberOfResultsOfAValidResponse()
    {
        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws.xml');

        $response = new Response(200, [], $fixtures);
        $mock     = new MockHandler([$response]);

        $handler      = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 121518297);
        $productListing->validParameters();

        $plRequest = new ProductListingRequest($productListing);
        $result    = $plRequest->fetch();

        $this->assertEquals(1, $result->getTotalResultCount());
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetAllOffersOfAValidResponse()
    {
        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws_refproduct.xml');

        $response = new Response(200, [], $fixtures);
        $mock     = new MockHandler([$response]);

        $handler      = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 121518297);
        $productListing->setParameter('scope', 'PRICING');
        $productListing->validParameters();

        $plRequest = new ProductListingRequest($productListing);
        $result    = $plRequest->fetch();

        $offers = [
            [
                'price'        => 15.5,
                'shippingcost' => 4.6,
                'seller'       => 'Topslibris',
                'sellertype'   => 'PRO',
                'quality'      => 'NEW'
            ]
        ];

        $this->assertEquals($offers, $result->getOffers());
    }
}

