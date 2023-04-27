<?php
namespace esas\cmsgate\buynow\dao;

use esas\cmsgate\bridge\dao\ShopConfig;

class ShopConfigBuyNow extends ShopConfig
{
    private $name;

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