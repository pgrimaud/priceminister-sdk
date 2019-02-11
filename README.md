# PriceMinister SDK
[![Build Status](https://travis-ci.org/pgrimaud/priceminister-sdk.svg?branch=master)](https://travis-ci.org/pgrimaud/priceminister-sdk)
[![Packagist](https://img.shields.io/badge/packagist-install-brightgreen.svg)](https://packagist.org/packages/pgrimaud/priceminister-sdk)
[![Coverage Status](https://coveralls.io/repos/github/pgrimaud/priceminister-sdk/badge.svg?branch=master)](https://coveralls.io/github/pgrimaud/priceminister-sdk?branch=master)

## Usage

```
composer require pgrimaud/priceminister-sdk
```

```php

$client = new Priceminister\PriceministerClient('yourLogin', 'yourToken');

$productListing = new Priceminister\ProductListing($client);
$productListing->setParameter('kw', 'iron man tome 1 croire');
$productListing->validParameters();

$plRequest = new ProductListingRequest($productListing);

print_r($plRequest->fetch());
/*
Array
(
    [0] => Array
        (
            [id] => 283617873
            [values] => Array
                (
                    [name] => Iron Man Tome 1 - Croire
                    [image] => http://pmcdn.priceminister.com/photo/984727643_ML.jpg
                    [breadcrumb] => Livres > BD et livres d'humour > Comics
                    [caption] => 
                    [topic] => Livre
                    [offers] => 13
                    [bestprice] => 7
                )

        )

)
*/

$productListing2 = new ProductListing($client);
$productListing2->setParameter('productids', '283617873');
$productListing2->validParameters();

$plRequest = new ProductListingRequest($productListing2);
$result = $plRequest->fetch();

//Get price with shipping cost
echo $result->getBestPrice();
//11.5

//Get price without shipping cost
echo $result->getBestPrice(false);
//7.5

```
