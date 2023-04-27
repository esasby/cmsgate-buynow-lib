<?php
namespace esas\cmsgate\buynow\dao;

class BasketItemBuyNow
{
    private $id;
    private $basketId;
    /**
     * @var BasketBuyNow
     */
    private $basket;
    private $productId;
    /**
     * @var ProductBuyNow
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
     * @return BasketItemBuyNow
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
     * @return BasketItemBuyNow
     */
    public function setBasketId($basketId) {
        $this->basketId = $basketId;
        return $this;
    }

    /**
     * @return BasketBuyNow
     */
    public function getBasket() {
        return $this->basket;
    }

    /**
     * @param BasketBuyNow $basket
     * @return BasketItemBuyNow
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
     * @return BasketItemBuyNow
     */
    public function setProductId($productId) {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return ProductBuyNow
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * @param ProductBuyNow $product
     * @return BasketItemBuyNow
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
     * @return BasketItemBuyNow
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
     * @return BasketItemBuyNow
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
     * @return BasketItemBuyNow
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

}