<?php
namespace esas\cmsgate\wrappers;

use esas\cmsgate\bridge\OrderDataItemBuyNow;
use Throwable;

class OrderProductWrapperBuyNow extends OrderProductSafeWrapper
{
    /**
     * @var OrderDataItemBuyNow
     */
    private $orderItem;

    /**
     * OrderProductWrapperTilda constructor.
     * @param array
     */
    public function __construct($orderItem)
    {
        parent::__construct();
        $this->orderItem = $orderItem;
    }


    /**
     * Артикул товара
     * @throws Throwable
     * @return string
     */
    public function getInvIdUnsafe()
    {
        return $this->orderItem->getSku();
    }

    /**
     * Название или краткое описание товара
     * @throws Throwable
     * @return string
     */
    public function getNameUnsafe()
    {
        return $this->orderItem->getName();
    }

    /**
     * Количество товароа в корзине
     * @throws Throwable
     * @return mixed
     */
    public function getCountUnsafe()
    {
        return $this->orderItem->getCount();
    }

    /**
     * Цена за единицу товара
     * @throws Throwable
     * @return mixed
     */
    public function getUnitPriceUnsafe()
    {
        return $this->orderItem->getPrice();;
    }
}