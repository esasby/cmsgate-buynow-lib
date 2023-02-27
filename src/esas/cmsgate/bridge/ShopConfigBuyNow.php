<?php


namespace esas\cmsgate\bridge;

class ShopConfigBuyNow extends ShopConfig
{
    private $name;
    private $merchantId;
    private $orderCounter;
    /**
     * @var boolean
     */
    private $active;

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return ShopConfigBuyNow
     */
    public function setName($name) {
        $this->name = $name;
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
     * @return ShopConfigBuyNow
     */
    public function setMerchantId($merchantId) {
        $this->merchantId = $merchantId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderCounter() {
        return $this->orderCounter;
    }

    /**
     * @param mixed $orderCounter
     * @return ShopConfigBuyNow
     */
    public function setOrderCounter($orderCounter) {
        $this->orderCounter = $orderCounter;
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
     * @return ShopConfigBuyNow
     */
    public function setActive($active) {
        $this->active = $active;
        return $this;
    }
}