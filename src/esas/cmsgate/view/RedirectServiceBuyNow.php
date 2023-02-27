<?php


namespace esas\cmsgate\view;


use esas\cmsgate\utils\URLUtils;

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
        $location = URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_LOGIN;
        return $sendHeader ? self::redirect($location) : $location;
    }

    public function logoutPage($sendHeader = false) {
        $location = URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_LOGOUT;
        return $sendHeader ? self::redirect($location) : $location;
    }

    public function mainPage($sendHeader = false) {
        $location = URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_PRODUCTS;
        return $sendHeader ? self::redirect($location) : $location;
    }

    public static function basketList() {
        return URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_BASKETS;
    }

    public static function basketAdd() {
        return URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_BASKETS_ADD;
    }

    public static function basketEdit($basketId, $sendHeader = false) {
        $location = URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_BASKETS . '/' . $basketId;
        return $sendHeader ? self::redirect($location) : $location;
    }

    public static function basketDelete($basketId) {
        return URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_BASKETS . '/' . $basketId . '/delete';
    }

    public static function basketItemAdd($basketId) {
        return self::basketEdit($basketId) . '/items/add';
    }

    public static function basketItemDelete($basketId, $basketItemId) {
        return self::basketItemEdit($basketId, $basketItemId) . '/delete';
    }

    public static function basketItemEdit($basketId, $basketItemId) {
        return self::basketEdit($basketId) . '/items/' . $basketItemId;
    }

    public static function shopConfigList() {
        return URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_SHOP_CONFIGS;
    }

    public static function shopConfigAdd() {
        return URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_SHOP_CONFIGS_ADD;
    }

    public static function shopConfigEdit($shopConfigId) {
        return URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_SHOP_CONFIGS . '/' . $shopConfigId;
    }

    public static function shopConfigDelete($shopConfig) {
        return URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_SHOP_CONFIGS . '/' . $shopConfig . '/delete';
    }

    public static function productList() {
        return URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_PRODUCTS;
    }

    public static function productAdd() {
        return URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_PRODUCTS_ADD;
    }

    public static function productEdit($productId) {
        return URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_PRODUCTS . '/' . $productId;
    }

    public static function productDelete($productId) {
        return URLUtils::getCurrentURLMainPart() . self::PATH_ADMIN_PRODUCTS . '/' . $productId . '/delete';
    }
}