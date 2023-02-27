<?php


namespace esas\cmsgate\buynow;


use esas\cmsgate\BridgeConnector;

class BuyNowBasketItem
{
    private $id;
    private $basketId;
    /**
     * @var BuyNowBasket
     */
    private $basket;
    private $productId;
    /**
     * @var BuyNowProduct
     */
    private $product;
    private $count;
    private $maxCount;
    private $createdAt;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return BuyNowBasketItem
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBasketId() {
        return $this->basketId;
    }

    /**
     * @param mixed $basketId
     * @return BuyNowBasketItem
     */
    public function setBasketId($basketId) {
        $this->basketId = $basketId;
        return $this;
    }

    /**
     * @return BuyNowBasket
     */
    public function getBasket() {
        return $this->basket;
    }

    /**
     * @param BuyNowBasket $basket
     * @return BuyNowBasketItem
     */
    public function setBasket($basket) {
        $this->basket = $basket;
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
     * @return BuyNowBasketItem
     */
    public function setProductId($productId) {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return BuyNowProduct
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * @param BuyNowProduct $product
     * @return BuyNowBasketItem
     */
    public function setProduct($product) {
        $this->product = $product;
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
     * @return BuyNowBasketItem
     */
    public function setCount($count) {
        $this->count = $count;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxCount() {
        return $this->maxCount;
    }

    /**
     * @param mixed $maxCount
     * @return BuyNowBasketItem
     */
    public function setMaxCount($maxCount) {
        $this->maxCount = $maxCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return BuyNowBasketItem
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

}