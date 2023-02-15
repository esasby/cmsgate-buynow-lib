<?php
namespace esas\cmsgate\protocol;


class RequestParamsBuyNow
{
    const ORDER_ID = 'orderId';
    const BASKET_ID = 'basketId';
    const BASKET_NAME = 'basketName';
    const BASKET_DESCRIPTION = 'basketDescription';
    const BASKET_ACTIVE = 'basketActive';
    const CUSTOMER_FIO = 'customerFio';
    const CUSTOMER_PHONE = 'customerPhone';
    const CUSTOMER_EMAIL = 'customerEmail';
    const BASKET_ITEMS = 'basketItems';
    const PRODUCT_ID = 'productId';
    const PRODUCT_SKU = 'productSKU';
    const PRODUCT_NAME = 'productName';
    const PRODUCT_DESCRIPTION = 'productDescription';
    const PRODUCT_ACTIVE = 'productActive';
    const PRODUCT_PRICE = 'productPrice';
    const PRODUCT_CURRENCY = 'productCurrency';

    public static function getProductId() {
        return $_REQUEST[self::PRODUCT_ID];
    }

    public static function getProductSKU() {
        return $_REQUEST[self::PRODUCT_SKU];
    }


    public static function getProductName() {
        return $_REQUEST[self::PRODUCT_NAME];
    }

    public static function getProductDescription() {
        return $_REQUEST[self::PRODUCT_DESCRIPTION];
    }

    public static function getProductActive() {
        return $_REQUEST[self::PRODUCT_ACTIVE];
    }

    public static function getProductPrice() {
        return $_REQUEST[self::PRODUCT_PRICE];
    }

    public static function getProductCurrency() {
        return $_REQUEST[self::PRODUCT_CURRENCY];
    }

    public static function getBasketId() {
        return $_REQUEST[self::BASKET_ID];
    }

    public static function getOrderId() {
        return $_REQUEST[self::ORDER_ID];
    }

    public static function getCustomerFIO() {
        return $_REQUEST[self::CUSTOMER_FIO];
    }

    public static function getCustomerEmail() {
        return $_REQUEST[self::CUSTOMER_EMAIL];
    }

    public static function getCustomerPhone() {
        return $_REQUEST[self::CUSTOMER_PHONE];
    }

    public static function getBasketItems() {
        return $_REQUEST[self::BASKET_ITEMS];
    }
}