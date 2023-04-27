<?php
namespace esas\cmsgate\buynow\dao;

class OrderDataItemBuyNow
{
    public $sku;
    public $productId;
    public $name;
    public $count;
    public $price;

    /**
     * @return mixed
     */
    public function getSku() {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     * @return OrderDataItemBuyNow
     */
    public function setSku($sku) {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductId() {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     * @return OrderDataItemBuyNow
     */
    public function setProductId($productId) {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return OrderDataItemBuyNow
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCount() {
        return $this->count;
    }

    /**
     * @param mixed $count
     * @return OrderDataItemBuyNow
     */
    public function setCount($count) {
        $this->count = $count;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return OrderDataItemBuyNow
     */
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }


}