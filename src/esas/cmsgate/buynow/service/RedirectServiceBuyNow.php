<?php


namespace esas\cmsgate\buynow\service;


use esas\cmsgate\bridge\service\RedirectServiceBridge;
use esas\cmsgate\Registry;

class RedirectServiceBuyNow extends RedirectServiceBridge
{
    const PATH_MAIN = '/admin';
    const PATH_ADMIN_LOGIN = '/admin/login';
    const PATH_ADMIN_LOGOUT = '/admin/logout';
    const PATH_ADMIN_SHOP_CONFIGS = '/admin/shop_configs';
    const PATH_ADMIN_SHOP_CONFIGS_ADD = '/admin/shop_configs/add';
    const PATH_ADMIN_PRODUCTS = '/admin/products';
    const PATH_ADMIN_PRODUCTS_ADD = '/admin/products/add';
    const PATH_ADMIN_BASKETS = '/admin/baskets';
    const PATH_ADMIN_BASKETS_ADD = '/admin/baskets/add';
    const PATH_ADMIN_ORDERS = '/admin/orders';

    const PATH_CLIENT_BASKETS = "/baskets";
    const PATH_CLIENT_ORDERS = "/orders";

    /**
     * @return $this
     * @throws \esas\cmsgate\utils\CMSGateException
     */
    public static function fromRegistry() {
        return Registry::getRegistry()->getService(RedirectService::class, new RedirectServiceBuyNow());
    }

    public function loginPage($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_LOGIN, $sendHeader);
    }

    public function logoutPage($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_LOGOUT, $sendHeader);
    }

    public function mainPage($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_PRODUCTS, $sendHeader);
    }

    public function basketList($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_BASKETS, $sendHeader);
    }

    public function basketAdd($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_BASKETS_ADD, $sendHeader);
    }

    public function basketEdit($basketId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_BASKETS . '/' . $basketId, $sendHeader);
    }

    public function basketDelete($basketId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_BASKETS . '/' . $basketId . '/delete', $sendHeader);
    }

    public function basketItemAdd($basketId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_BASKETS . '/' . $basketId . '/items/add', $sendHeader);
    }

    public function basketItemDelete($basketId, $basketItemId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_BASKETS . '/' . $basketId . '/items/' . $basketItemId . '/delete', $sendHeader);
    }

    public function basketItemEdit($basketId, $basketItemId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_BASKETS . '/' . $basketId . '/items/' . $basketItemId, $sendHeader);
    }

    public function shopConfigList($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_SHOP_CONFIGS, $sendHeader);
    }

    public function shopConfigAdd($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_SHOP_CONFIGS_ADD, $sendHeader);
    }

    public function shopConfigEdit($shopConfigId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_SHOP_CONFIGS . '/' . $shopConfigId, $sendHeader);
    }

    public function shopConfigDelete($shopConfig, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_SHOP_CONFIGS . '/' . $shopConfig . '/delete', $sendHeader);
    }

    public function productList($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_PRODUCTS, $sendHeader);
    }

    public function productAdd($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_PRODUCTS_ADD, $sendHeader);
    }

    public function productEdit($productId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_PRODUCTS . '/' . $productId, $sendHeader);
    }

    public function productDelete($productId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_PRODUCTS . '/' . $productId . '/delete', $sendHeader);
    }

    public static function orderList($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_ORDERS, $sendHeader);
    }

    public static function orderView($orderId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_ORDERS . '/' . $orderId, $sendHeader);
    }

    public static function clientBasketView($basketId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_CLIENT_BASKETS . '/' . $basketId, $sendHeader);
    }

    public static function clientBasketConfirm($basketId) {
        return self::returnAbsolutePathOrRedirect(self::PATH_CLIENT_BASKETS . '/' . $basketId);
    }

    public static function clientOrderView($orderId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_CLIENT_ORDERS . '/' . $orderId, $sendHeader);
    }
}