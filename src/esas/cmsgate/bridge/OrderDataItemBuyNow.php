<?php


namespace esas\cmsgate\bridge;


class OrderDataItemBuyNow
{
    private $sku;
    private $name;
    private $count;
    private $price;

    /**
     * @return mixed
     */
    public function getSku() {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     * @return OrderDataItemBuyNow
     */
    public function setSku($sku) {
        $this->sku = $sku;
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
     * @return OrderDataItemBuyNow
     */
    public function setName($name) {
        $this->name = $name;
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
     * @return OrderDataItemBuyNow
     */
    public function setCount($count) {
        $this->count = $count;
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
     * @return OrderDataItemBuyNow
     */
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }


}