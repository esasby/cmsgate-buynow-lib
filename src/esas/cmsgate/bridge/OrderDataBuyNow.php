<?php


namespace esas\cmsgate\bridge;


class OrderDataBuyNow
{
    public $orderId;
    public $basketId;
    public $amount;
    public $currency;
    public $customerFIO;
    public $customerPhone;
    public $customerEmail;

    /**
     * @var OrderDataItemBuyNow[]
     */
    public $items;

    /**
     * @return mixed
     */
    public function getOrderId() {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     * @return OrderDataBuyNow
     */
    public function setOrderId($orderId) {
        $this->orderId = $orderId;
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
     * @return OrderDataBuyNow
     */
    public function setBasketId($basketId) {
        $this->basketId = $basketId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     * @return OrderDataBuyNow
     */
    public function setAmount($amount) {
        $this->amount = $amount;
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
     * @return OrderDataBuyNow
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerFIO() {
        return $this->customerFIO;
    }

    /**
     * @param mixed $customerFIO
     * @return OrderDataBuyNow
     */
    public function setCustomerFIO($customerFIO) {
        $this->customerFIO = $customerFIO;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerPhone() {
        return $this->customerPhone;
    }

    /**
     * @param mixed $customerPhone
     * @return OrderDataBuyNow
     */
    public function setCustomerPhone($customerPhone) {
        $this->customerPhone = $customerPhone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerEmail() {
        return $this->customerEmail;
    }

    /**
     * @param mixed $customerEmail
     * @return OrderDataBuyNow
     */
    public function setCustomerEmail($customerEmail) {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    /**
     * @return OrderDataItemBuyNow[]
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * @param OrderDataItemBuyNow[] $items
     * @return OrderDataBuyNow
     */
    public function setItems($items) {
        $this->items = $items;
        return $this;
    }

    /**
     * @param OrderDataItemBuyNow $item
     * @return OrderDataBuyNow
     */
    public function addItem($item) {
        $this->items[] = $item;
        return $this;
    }
}