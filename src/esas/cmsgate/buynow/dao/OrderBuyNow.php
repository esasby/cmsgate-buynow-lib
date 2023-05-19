<?php
namespace esas\cmsgate\buynow\dao;

use esas\cmsgate\bridge\dao\Order;

class OrderBuyNow extends Order
{
    private $basketId;
    private $expiresAt;

    /**
     * @return mixed
     */
    public function getBasketId() {
        return $this->basketId;
    }

    /**
     * @param mixed $basketId
     * @return OrderBuyNow
     */
    public function setBasketId($basketId) {
        $this->basketId = $basketId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpiresAt() {
        return $this->expiresAt;
    }

    /**
     * @param mixed $expiresAt
     * @return OrderBuyNow
     */
    public function setExpiresAt($expiresAt) {
        $this->expiresAt = $expiresAt;
        return $this;
    }
}