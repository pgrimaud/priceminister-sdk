<?php

namespace Priceminister\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Priceminister\PriceministerClient;
use Priceminister\ProductListing;
use Priceminister\ProductListingRequest;

class ProductListingTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $priceministerClient;

    public function setUp()
    {
        $this->priceministerClient = new PriceministerClient('toto', 'p4ssw0rd');

        $fixtures = file_get_contents(__DIR__ . '/fixtures/listing_ssl_ws.xml');

        $response = new Response(200, [], $fixtures);
        $mock     = new MockHandler([$response]);

        $handler      = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testCreateProductListingWithInvalidParameters()
    {
        $this->expectException(\InvalidArgumentException::class);

        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('nop', 121518297);
        $productListing->validParameters();

        $plRequest = new ProductListingRequest($productListing);
        $plRequest->fetch();

    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testCreateProductListingWithValidParameters()
    {
        $productListing = new ProductListing($this->priceministerClient, $this->client);
        $productListing->setParameter('kw', 121518297);
        $productListing->validParameters();

        $plRequest = new ProductListingRequest($productListing);
        $plRequest->fetch();

        $ressource = 'https://ws.fr.shopping.rakuten.com/listing_ssl_ws?action=listing&login=toto&pwd=p4ssw0rd&version=2015-07-05&kw=121518297';

        $this->assertEquals($ressource, $plRequest->getRessource());
    }
}

