<?php

namespace esas\cmsgate\wrappers;

use esas\cmsgate\bridge\OrderCache;
use esas\cmsgate\bridge\OrderDataBuyNow;
use esas\cmsgate\OrderStatus;

class OrderWrapperBuyNow extends OrderWrapperCached
{
    protected $products;

    /**
     * @var OrderDataBuyNow
     */
    protected $orderData;

    /**
     * @param $orderCache OrderCache
     */
    public function __construct($orderCache)
    {
        parent::__construct($orderCache);
        $this->orderData = $orderCache->getOrderData();
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
     * Массив товаров в заказе
     * @return \esas\cmsgate\wrappers\OrderProductWrapperBuyNow[]
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
     * Текущий статус заказа в CMS
     * @return mixed
     */
    public function getStatusUnsafe()
    {
        return OrderStatus::pending();
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