<?php


namespace esas\cmsgate\controllers\admin;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\controllers\ControllerBridgeLogin;
use esas\cmsgate\controllers\ControllerBridgeLogout;
use esas\cmsgate\view\RedirectServiceBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\StringUtils;
use Exception;
use Throwable;

class AdminControllerBuyNow extends Controller
{

    public function process() {
        $request = $_SERVER['REDIRECT_URL'];
        $controller = null;
        if (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_ADMIN_LOGIN)) {
            $controller = new ControllerBridgeLogin();
        } elseif (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_ADMIN_LOGOUT)) {
            $controller = new ControllerBridgeLogout();
        } elseif (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_MAIN)) {
            $controller = new AdminControllerBuyNowCabinet();
        } elseif (StringUtils::contains($request, RedirectServiceBuyNow::PATH_ADMIN_SHOP_CONFIGS)) {
            $controller = new AdminControllerBuyNowShopConfigs();
        } elseif (StringUtils::contains($request, RedirectServiceBuyNow::PATH_ADMIN_BASKETS)) {
            if (StringUtils::contains($request, 'items')) {
                $controller = new AdminControllerBuyNowBasketItems();
            } else {
                $controller = new AdminControllerBuyNowBaskets();
            }
        } elseif (StringUtils::contains($request, RedirectServiceBuyNow::PATH_ADMIN_PRODUCTS)) {
            $controller = new AdminControllerBuyNowProducts();
        } else {
            http_response_code(404);
        }
        $controller->process();
    }
}