<?php


namespace esas\cmsgate\buynow;


use esas\cmsgate\bridge\ShopConfigBuyNow;

class BuyNowBasket
{
    private $id;
    private $shopConfigId;
    /**
     * @var ShopConfigBuyNow
     */
    private $shopConfig;
    private $name;
    private $description;
    /**
     * @var boolean
     */
    private $active;
    /**
     * @var boolean
     */
    private $askPhone = false;
    /**
     * @var boolean
     */
    private $askEmail = false;
    /**
     * @var boolean
     */
    private $askFIO = false;

    private $clientUICss;
    private $returnUrl;
    private $createdAt;
    private $checkoutCount;
    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return BuyNowBasket
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShopConfigId() {
        return $this->shopConfigId;
    }

    /**
     * @param mixed $shopConfigId
     * @return BuyNowBasket
     */
    public function setShopConfigId($shopConfigId) {
        $this->shopConfigId = $shopConfigId;
        return $this;
    }

    /**
     * @return ShopConfigBuyNow
     */
    public function getShopConfig() {
        return $this->shopConfig;
    }

    /**
     * @param ShopConfigBuyNow $shopConfig
     * @return BuyNowBasket
     */
    public function setShopConfig($shopConfig) {
        $this->shopConfig = $shopConfig;
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
     * @return BuyNowBasket
     */
    public function setName($name) {
        $this->name = $name;
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
     * @return BuyNowBasket
     */
    public function setDescription($description) {
        $this->description = $description;
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
     * @return BuyNowBasket
     */
    public function setActive($active) {
        $this->active = $active;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAskPhone() {
        return $this->askPhone;
    }

    /**
     * @param bool $askPhone
     * @return BuyNowBasket
     */
    public function setAskPhone($askPhone) {
        $this->askPhone = $askPhone;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAskEmail() {
        return $this->askEmail;
    }

    /**
     * @param bool $askEmail
     * @return BuyNowBasket
     */
    public function setAskEmail($askEmail) {
        $this->askEmail = $askEmail;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAskFIO() {
        return $this->askFIO;
    }

    /**
     * @param bool $askFIO
     * @return BuyNowBasket
     */
    public function setAskFIO($askFIO) {
        $this->askFIO = $askFIO;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientUICss() {
        return $this->clientUICss;
    }

    /**
     * @param mixed $clientUICss
     * @return BuyNowBasket
     */
    public function setClientUICss($clientUICss) {
        $this->clientUICss = $clientUICss;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReturnUrl() {
        return $this->returnUrl;
    }

    /**
     * @param mixed $returnUrl
     * @return BuyNowBasket
     */
    public function setReturnUrl($returnUrl) {
        $this->returnUrl = $returnUrl;
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
     * @return BuyNowBasket
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCheckoutCount() {
        return $this->checkoutCount;
    }

    /**
     * @param mixed $checkoutCount
     * @return BuyNowBasket
     */
    public function setCheckoutCount($checkoutCount) {
        $this->checkoutCount = $checkoutCount;
        return $this;
    }
}