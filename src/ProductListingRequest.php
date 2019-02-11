<?php

namespace Priceminister;

use GuzzleHttp\ClientInterface;

class ProductListingRequest
{
    const ENDPOINT = 'https://ws.fr.shopping.rakuten.com/listing_ssl_ws?action=listing';
    const VERSION = '2015-07-05';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var PriceministerClient
     */
    private $priceministerClient;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var string
     */
    private $ressource = '';

    /**
     * @var string
     */
    private $response = '';

    /**
     * ProductListingRequest constructor.
     * @param ProductListing $productListing
     */
    public function __construct(ProductListing $productListing)
    {
        $this->client              = $productListing->getClient();
        $this->priceministerClient = $productListing->getPriceministerClient();
        $this->parameters          = $productListing->getParameters();

        $this->createRessource();
    }

    private function createRessource()
    {
        $this->ressource = $this->setCredentials();

        foreach ($this->parameters as $key => $parameter) {
            $this->ressource .= '&' . $key . '=' . $parameter;
        }
    }

    private function setCredentials()
    {
        return self::ENDPOINT .
            '&login=' . $this->priceministerClient->getLogin() .
            '&pwd=' . $this->priceministerClient->getPassword() .
            '&version=' . self::VERSION;
    }

    /**
     * @return ProductListingParser
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function fetch()
    {
        $request  = $this->client->request('GET', $this->ressource);
        $response = (string)$request->getBody();

        return new ProductListingParser($response);
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getRessource()
    {
        return $this->ressource;
    }
}
