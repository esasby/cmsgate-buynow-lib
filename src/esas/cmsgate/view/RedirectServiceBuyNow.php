<?php


namespace esas\cmsgate\view;


class RedirectServiceBuyNow extends RedirectService
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

    public function loginPage($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_LOGIN, $sendHeader);
    }

    public function logoutPage($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_LOGOUT, $sendHeader);
    }

    public function mainPage($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_PRODUCTS, $sendHeader);
    }

    public static function basketList($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_BASKETS, $sendHeader);
    }

    public static function basketAdd($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_BASKETS_ADD, $sendHeader);
    }

    public static function basketEdit($basketId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_BASKETS . '/' . $basketId, $sendHeader);
    }

    public static function basketDelete($basketId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_BASKETS . '/' . $basketId . '/delete', $sendHeader);
    }

    public static function basketItemAdd($basketId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::basketEdit($basketId) . '/items/add', $sendHeader);
    }

    public static function basketItemDelete($basketId, $basketItemId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::basketItemEdit($basketId, $basketItemId) . '/delete', $sendHeader);
    }

    public static function basketItemEdit($basketId, $basketItemId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::basketEdit($basketId) . '/items/' . $basketItemId, $sendHeader);
    }

    public static function shopConfigList($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_SHOP_CONFIGS, $sendHeader);
    }

    public static function shopConfigAdd($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_SHOP_CONFIGS_ADD, $sendHeader);
    }

    public static function shopConfigEdit($shopConfigId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_SHOP_CONFIGS . '/' . $shopConfigId, $sendHeader);
    }

    public static function shopConfigDelete($shopConfig, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_SHOP_CONFIGS . '/' . $shopConfig . '/delete', $sendHeader);
    }

    public static function productList($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_PRODUCTS, $sendHeader);
    }

    public static function productAdd($sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_PRODUCTS_ADD, $sendHeader);
    }

    public static function productEdit($productId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_PRODUCTS . '/' . $productId, $sendHeader);
    }

    public static function productDelete($productId, $sendHeader = false) {
        return self::returnAbsolutePathOrRedirect(self::PATH_ADMIN_PRODUCTS . '/' . $productId . '/delete', $sendHeader);
    }
}