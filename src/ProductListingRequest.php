<?php
namespace Priceminister;

use GuzzleHttp\ClientInterface;

class ProductListingRequest
{
    const ENDPOINT = 'https://ws.priceminister.com/listing_ssl_ws?action=listing';
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
     * @param ClientInterface $client
     * @param PriceministerClient $priceministerClient
     * @param $parameters
     */
    public function __construct(ClientInterface $client, PriceministerClient $priceministerClient, $parameters)
    {
        $this->client = $client;
        $this->priceministerClient = $priceministerClient;
        $this->parameters = $parameters;

        $this->createRessource();
        $this->getRessource();
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

    private function getRessource()
    {
        $request = $this->client->request('GET', $this->ressource);
        $this->response = (string) $request->getBody();
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }
}
