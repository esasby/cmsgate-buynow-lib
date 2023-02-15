<?php


namespace esas\cmsgate\buynow;


use esas\cmsgate\BridgeConnector;

class BuyNowBasket
{
    private $id;
    private $shopConfigId;
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
    private $createdAt;
    private $checkoutCount;

    /**
     * @var BuyNowBasketItem[]
     */
    private $items;
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

    /**
     * @return BuyNowBasketItem[]
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * @param BuyNowBasketItem[] $items
     * @return BuyNowBasket
     */
    public function setItems($items) {
        $this->items = $items;
        return $this;
    }

    /**
     * @param BuyNowBasketItem $item
     * @return BuyNowBasket
     */
    public function addItem($item) {
        $this->items[] = $item;
        return $this;
    }
}