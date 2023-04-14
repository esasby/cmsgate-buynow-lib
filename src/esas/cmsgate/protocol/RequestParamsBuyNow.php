<?php
namespace esas\cmsgate\protocol;


class RequestParamsBuyNow
{
    const ORDER_ID = 'orderId';
    const BASKET_ID = 'basketId';
    const BASKET_SHOP_CONFIG_ID = 'basketShopConfigId';
    const BASKET_NAME = 'basketName';
    const BASKET_DESCRIPTION = 'basketDescription';
    const BASKET_ACTIVE = 'basketActive';
    const BASKET_ASK_NAME = 'basketAskName';
    const BASKET_ASK_EMAIL = 'basketAskEmail';
    const BASKET_ASK_PHONE = 'basketAskPhone';
    const BASKET_RETURN_URL = 'basketReturnUrl';
    const CLIENT_UI_CSS = 'clientUICss';
    const BASKET_ITEM_ID = 'basketItemId';
    const BASKET_ITEM_PRODUCT_COUNT = 'basketItemProductsCount';
    const BASKET_ITEM_PRODUCT_MAX_COUNT = 'basketItemProductsMaxCount';
    const CUSTOMER_FIO = 'customerFio';
    const CUSTOMER_PHONE = 'customerPhone';
    const CUSTOMER_EMAIL = 'customerEmail';
    const BASKET_PRODUCT_COUNT = 'basketProductCount';
    const PRODUCT_ID = 'productId';
    const PRODUCT_SKU = 'productSKU';
    const PRODUCT_NAME = 'productName';
    const PRODUCT_DESCRIPTION = 'productDescription';
    const PRODUCT_ACTIVE = 'productActive';
    const PRODUCT_PRICE = 'productPrice';
    const PRODUCT_CURRENCY = 'productCurrency';
    const PRODUCT_IMAGE = 'productImage';
    const SHOP_CONFIG_ID = 'shopConfigId';
    const SHOP_CONFIG_NAME = 'shopConfigName';
    const SHOP_CONFIG_ACTIVE = 'shopConfigActive';

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

    public static function getProductImage() {
        return $_REQUEST[self::PRODUCT_IMAGE];
    }

    public static function getProductCurrency() {
        return $_REQUEST[self::PRODUCT_CURRENCY];
    }

    public static function getBasketId() {
        return $_REQUEST[self::BASKET_ID];
    }

    public static function getBasketItemId() {
        return $_REQUEST[self::BASKET_ITEM_ID];
    }

    public static function getBasketReturnUrl() {
        return $_REQUEST[self::BASKET_RETURN_URL];
    }

    public static function getClientUICss() {
        return $_REQUEST[self::CLIENT_UI_CSS];
    }

    public static function getBasketShopConfigId() {
        return $_REQUEST[self::BASKET_SHOP_CONFIG_ID];
    }

    public static function getBasketName() {
        return $_REQUEST[self::BASKET_NAME];
    }

    public static function getBasketDescription() {
        return $_REQUEST[self::BASKET_DESCRIPTION];
    }

    public static function getBasketActive() {
        return $_REQUEST[self::BASKET_ACTIVE];
    }

    public static function getBasketAskName() {
        return $_REQUEST[self::BASKET_ASK_NAME];
    }

    public static function getBasketAskEmail() {
        return $_REQUEST[self::BASKET_ASK_EMAIL];
    }

    public static function getBasketAskPhone() {
        return $_REQUEST[self::BASKET_ASK_PHONE];
    }

    public static function getBasketItemProductCount() {
        return $_REQUEST[self::BASKET_ITEM_PRODUCT_COUNT];
    }

    public static function getBasketItemProductMaxCount() {
        return $_REQUEST[self::BASKET_ITEM_PRODUCT_MAX_COUNT];
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

    public static function getBasketProductCount($productId) {
        return $_REQUEST[self::getBasketProductCountInputId($productId)];
    }

    public static function getBasketProductCountInputId($productId) {
        return self::BASKET_PRODUCT_COUNT . '#' . $productId;
    }

    public static function getShopConfigId() {
        return $_REQUEST[self::SHOP_CONFIG_ID];
    }

    public static function getShopConfigName() {
        return $_REQUEST[self::SHOP_CONFIG_NAME];
    }

    public static function getShopConfigActive() {
        return $_REQUEST[self::SHOP_CONFIG_ACTIVE];
    }
}