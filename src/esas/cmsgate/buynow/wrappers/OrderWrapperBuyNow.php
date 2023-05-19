<?php

namespace esas\cmsgate\buynow\wrappers;

use esas\cmsgate\bridge\dao\Order;
use esas\cmsgate\buynow\dao\OrderDataBuyNow;
use esas\cmsgate\bridge\wrappers\OrderWrapperCached;
use esas\cmsgate\OrderStatus;

class OrderWrapperBuyNow extends OrderWrapperCached
{
    protected $products;

    /**
     * @var OrderDataBuyNow
     */
    protected $orderData;

    /**
     * @param $order Order
     */
    public function __construct($order)
    {
        parent::__construct($order);
        $this->orderData = $order->getOrderData();
    }

    /**
     * Уникальный идентификатор заказ в рамках CMS.
     * В Tilda это <номеро проекта>:<номер заказа>
     * @return string
     */
    public function getOrderIdUnsafe()
    {
        return $this->orderData->getOrderId();
    }

    /**
     * Полное имя покупателя
     * @return string
     */
    public function getFullNameUnsafe()
    {
        return $this->orderData->getCustomerFIO();
    }

    /**
     * Мобильный номер покупателя для sms-оповещения
     * (если включено администратором)
     * @return string
     */
    public function getMobilePhoneUnsafe()
    {
        return $this->orderData->getCustomerPhone();
    }

    /**
     * Email покупателя для email-оповещения
     * (если включено администратором)
     * @return string
     */
    public function getEmailUnsafe()
    {
        return $this->orderData->getCustomerEmail();
    }

    /**
     * Физический адрес покупателя
     * @return string
     */
    public function getAddressUnsafe()
    {
        return '';
    }

    /**
     * Общая сумма товаров в заказе
     * @return string
     */
    public function getAmountUnsafe()
    {
        return $this->orderData->getAmount();
    }


    public function getShippingAmountUnsafe()
    {
        return 0; //todo
    }

    /**
     * Валюта заказа (буквенный код)
     * @return string
     */
    public function getCurrencyUnsafe()
    {
        return $this->orderData->getCurrency();
    }

    /**
     * @return OrderDataBuyNow
     */
    public function getOrderData() {
        return $this->orderData;
    }

    /**
     * Массив товаров в заказе
     * @return OrderProductWrapperBuyNow[]
     */
    public function getProductsUnsafe()
    {
        if ($this->products != null)
            return $this->products;
        foreach ($this->orderData->getItems() as $basketItem)
            $this->products[] = new OrderProductWrapperBuyNow($basketItem);
        return $this->products;
    }

    /**
     * Идентификатор клиента
     * @return string
     */
    public function getClientIdUnsafe()
    {
        return 'UNKNOWN';
    }

}