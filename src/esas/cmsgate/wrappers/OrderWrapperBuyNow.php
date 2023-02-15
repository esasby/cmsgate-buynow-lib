<?php

namespace esas\cmsgate\wrappers;

use esas\cmsgate\OrderStatus;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\utils\StringUtils;
use Throwable;

class OrderWrapperBuyNow extends OrderWrapperCached
{
    protected $products;

    /**
     * @param $order
     */
    public function __construct($orderCache)
    {
        parent::__construct($orderCache);
    }

    /**
     * Уникальный идентификатор заказ в рамках CMS.
     * В Tilda это <номеро проекта>:<номер заказа>
     * @return string
     */
    public function getOrderIdUnsafe()
    {
        return $this->orderCache->getUuid();
    }

    /**
     * Полное имя покупателя
     * @return string
     */
    public function getFullNameUnsafe()
    {
        return $this->orderCache->getOrderData()[RequestParamsBuyNow::CUSTOMER_FIO];
    }

    /**
     * Мобильный номер покупателя для sms-оповещения
     * (если включено администратором)
     * @return string
     */
    public function getMobilePhoneUnsafe()
    {
        return $this->orderCache->getOrderData()[RequestParamsBuyNow::CUSTOMER_PHONE];
    }

    /**
     * Email покупателя для email-оповещения
     * (если включено администратором)
     * @return string
     */
    public function getEmailUnsafe()
    {
        return $this->orderCache->getOrderData()[RequestParamsBuyNow::CUSTOMER_EMAIL];
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
        return $this->orderCache->getOrderData()[RequestParamsTilda::ORDER_AMOUNT];
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
        return $this->orderCache->getOrderData()[RequestParamsTilda::ORDER_CURRENCY];
    }

    /**
     * Массив товаров в заказе
     * @return \esas\cmsgate\wrappers\OrderProductWrapperTilda[]
     */
    public function getProductsUnsafe()
    {
        if ($this->products != null)
            return $this->products;
        $items = json_decode($this->orderCache->getOrderData()[RequestParamsTilda::ORDER_ITEMS], true);
        foreach ($items as $basketItem)
            $this->products[] = new OrderProductWrapperTilda($basketItem);
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