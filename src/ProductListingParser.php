<?php
namespace Priceminister;

class ProductListingParser
{
    /**
     * @var string
     */
    private $body;

    /**
     * @var object
     */
    private $data;

    public function __construct($body)
    {
        $this->body = $body;
        $this->validateXML();
    }

    /**
     * @throws \Exception
     */
    private function validateXML()
    {
        $this->data = simplexml_load_string($this->body);

        if ($this->data->response->totalresultcount == 0) {
            throw new \Exception('No products');
        }
    }

    /**
     * @param bool $withShippingCost
     * @return float
     */
    public function getBestPrice($withShippingCost = true)
    {
        if ($this->data->response->products->product->offercounts->total > 0) {
            $price = (float)$this->data->response->products->product->bestprices->global->advertprice->amount;
            $shippingCost = (float)$this->data->response->products->product->bestprices->global->shippingcost->amount;
            if ($withShippingCost) {
                return round($price + $shippingCost, 2);
            } else {
                return round($price, 2);
            }
        } else {
            return 0;
        }
    }

    /**
     * @return string
     */
    public function getHeadline()
    {
        return (string)$this->data->response->products->product->headline;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return (string)$this->data->response->products->product->productid;
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        $products = [];
        if (count($this->data->response->products->product) > 0) {
            foreach ($this->data->response->products->product as $product) {
                $products[] = [
                    'id' => (string)$product->productid,
                    'name' => (string)$product->headline
                ];
            }
        }
        return $products;
    }
}
