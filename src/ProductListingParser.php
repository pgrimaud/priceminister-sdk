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
        //show($this->data);
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
                    'values' => [
                        'name' => (string)$product->headline,
                        'image' => (string)$product->image->url,
                        'breadcrumb' => $this->getBreadCrumb($product->breadcrumbselements),
                        'caption' => (string)$product->caption,
                        'topic' => (string)$product->topic,
                        'offers' => (int)$product->offercounts->total,
                        'bestprice' => (int)$product->bestprices->global->advertprice->amount
                    ]
                ];
            }
        }
        return $products;
    }

    /**
     * @return int
     */
    public function getTotalResultCount()
    {
        return (int)$this->data->response->totalresultcount;
    }

    /**
     * @param $product
     * @return string
     */
    private function getBreadCrumb($product)
    {
        $return = '';
        foreach ($product->breadcrumbselement as $breadCrumb) {
            $return .= (string)$breadCrumb->label . ' > ';
        }
        return substr($return, 0, -3);
    }

    /**
     * @return int
     */
    public function getPageNumber()
    {
        return (int)$this->data->request->pagenumber;
    }
}
