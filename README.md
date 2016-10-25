# PriceMinister SDK
[![Build Status](https://travis-ci.org/pgrimaud/priceminister-sdk.svg?branch=master)](https://travis-ci.org/pgrimaud/priceminister-sdk)
[![Packagist](https://img.shields.io/badge/packagist-install-brightgreen.svg)](https://packagist.org/packages/pgrimaud/priceminister-sdk)
[![Code Climate](https://codeclimate.com/github/pgrimaud/priceminister-sdk/badges/gpa.svg)](https://codeclimate.com/github/pgrimaud/priceminister-sdk)
[![Test Coverage](https://codeclimate.com/github/pgrimaud/priceminister-sdk/badges/coverage.svg)](https://codeclimate.com/github/pgrimaud/priceminister-sdk/coverage)
[![Issue Count](https://codeclimate.com/github/pgrimaud/priceminister-sdk/badges/issue_count.svg)](https://codeclimate.com/github/pgrimaud/priceminister-sdk)
## Usage

```
composer require pgrimaud/priceminister-sdk
```

```php

$client = new Priceminister\PriceministerClient('yourLogin', 'yourToken');

$productListing = new Priceminister\ProductListing($client);
$productListing->setParameter('kw', 'iron man tome 1 croire');
$result = $productListing->request();

print_r($result->getProducts());
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
$result = $productListing->request();

//Get price with shipping cost
echo $result->getBestPrice();
//11.5

//Get price without shipping cost
echo $result->getBestPrice(false);
//7.5

```