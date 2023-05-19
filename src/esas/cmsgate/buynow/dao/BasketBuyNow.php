<?php


namespace esas\cmsgate\buynow\dao;



use DateTime;

class BasketBuyNow
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
    private $expiresAt;
    private $checkoutCount;
    private $paidMaxCount;
    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return BasketBuyNow
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
     * @return BasketBuyNow
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
     * @return BasketBuyNow
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
     * @return BasketBuyNow
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
     * @return BasketBuyNow
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
     * @return BasketBuyNow
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
     * @return BasketBuyNow
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
     * @return BasketBuyNow
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
     * @return BasketBuyNow
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
     * @return BasketBuyNow
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
     * @return BasketBuyNow
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
     * @return BasketBuyNow
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpiresAt() {
        return $this->expiresAt;
    }

    /**
     * @param DateTime $expiresAt
     * @return BasketBuyNow
     */
    public function setExpiresAt($expiresAt) {
        $this->expiresAt = $expiresAt == '' ? null : $expiresAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaidMaxCount() {
        return $this->paidMaxCount;
    }

    /**
     * @param mixed $paidMaxCount
     * @return BasketBuyNow
     */
    public function setPaidMaxCount($paidMaxCount) {
        $this->paidMaxCount = $paidMaxCount;
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
     * @return BasketBuyNow
     */
    public function setCheckoutCount($checkoutCount) {
        $this->checkoutCount = $checkoutCount;
        return $this;
    }
}