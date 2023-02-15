<?php


namespace esas\cmsgate\controllers\admin;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\controllers\ControllerBridgeLogin;
use esas\cmsgate\controllers\ControllerBridgeLogout;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\StringUtils;
use Exception;
use Throwable;

class AdminControllerBuyNow extends Controller
{
    const PATH_ADMIN = '/admin';
    const PATH_ADMIN_LOGIN = '/admin/login';
    const PATH_ADMIN_LOGOUT = '/admin/logout';
    const PATH_ADMIN_SHOPS = '/admin/shops'; //list, add, edit, delete
    const PATH_ADMIN_BASKETS = '/admin/baskets'; //list, add, edit, delete, product-add, product-delete

    public function process() {
        $request = $_SERVER['REDIRECT_URL'];
        if (StringUtils::endsWith($request, self::PATH_ADMIN_LOGIN)) {
            $controller = new ControllerBridgeLogin();
            $controller->process();
        } elseif (StringUtils::endsWith($request, self::PATH_ADMIN_LOGOUT)) {
            $controller = new ControllerBridgeLogout();
            $controller->process();
        } elseif (StringUtils::endsWith($request, self::PATH_ADMIN)) {
            $controller = new AdminControllerBuyNowCabinet();
            $controller->process();
        } elseif (StringUtils::contains($request, self::PATH_ADMIN_SHOPS)) {
            $controller = new AdminControllerBuyNowShopConfigs();
            $controller->process();
        } elseif (StringUtils::contains($request, self::PATH_ADMIN_BASKETS)) {
            $controller = new AdminControllerBuyNowBaskets();
            $controller->process();
        } elseif (StringUtils::contains($request, AdminControllerBuyNowProducts::PATH_ADMIN_PRODUCTS)) {
            $controller = new AdminControllerBuyNowProducts();
            $controller->process();
        } else {
            http_response_code(404);
        }
    }
}