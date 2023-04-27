<?php
namespace esas\cmsgate\buynow\dao;

class ProductBuyNow
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
    private $image;
    private $createdAt;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return ProductBuyNow
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
     * @return ProductBuyNow
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
     * @return ProductBuyNow
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
     * @return ProductBuyNow
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
     * @return ProductBuyNow
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
     * @return ProductBuyNow
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
     * @return ProductBuyNow
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return ProductBuyNow
     */
    public function setImage($image) {
        $this->image = $image;
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
     * @return ProductBuyNow
     */
    public function setActive($active) {
        $this->active = boolval($active);
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
     * @return ProductBuyNow
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

}