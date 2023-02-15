<?php


namespace esas\cmsgate\buynow;


use esas\cmsgate\BridgeConnector;

class BuyNowProduct
{
    private $id;
    private $merchantId;
    private $name;
    private $sku;
    private $description;
    private $price;
    private $currency;
    /**
     * @var boolean
     */
    private $active;
    private $createdAt;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return BuyNowProduct
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMerchantId() {
        return $this->merchantId;
    }

    /**
     * @param mixed $merchantId
     * @return BuyNowProduct
     */
    public function setMerchantId($merchantId) {
        $this->merchantId = $merchantId;
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
     * @return BuyNowProduct
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSku() {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     * @return BuyNowProduct
     */
    public function setSku($sku) {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return BuyNowProduct
     */
    public function setDescription($description) {
        $this->description = $description;
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
     * @return BuyNowProduct
     */
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     * @return BuyNowProduct
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive() {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return BuyNowProduct
     */
    public function setActive($active) {
        $this->active = $active;
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
     * @return BuyNowProduct
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

}