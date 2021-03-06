<?php

namespace Priceminister;

use GuzzleHttp\Client;

class ProductListing
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var PriceministerClient
     */
    private $priceministerClient;

    /**
     * @var array
     */
    private $parameters = [];

    public function __construct(PriceministerClient $priceministerClient, $client = null)
    {
        $this->client              = $client ?: new Client();
        $this->priceministerClient = $priceministerClient;
    }

    /**
     * @param $field
     * @param $parameter
     */
    public function setParameter($field, $parameter)
    {
        $this->parameters[$field] = $parameter;
    }

    public function validParameters()
    {
        $hasAllowedParameters = false;

        $allowedParameters = [
            'kw',
            'nav',
            'refs',
            'productids',
            'pagenumber'
        ];

        foreach ($this->parameters as $key => $parameter) {
            if (in_array($key, $allowedParameters) && !empty($parameter)) {
                $hasAllowedParameters = true;
            }
        }

        if (!$hasAllowedParameters) {
            throw new \InvalidArgumentException('Missing one valid parameter');
        }
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return PriceministerClient
     */
    public function getPriceministerClient()
    {
        return $this->priceministerClient;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
