<?php


namespace esas\cmsgate\buynow\controllers\admin;


use esas\cmsgate\bridge\controllers\ControllerBridgeLogin;
use esas\cmsgate\bridge\controllers\ControllerBridgeLogout;
use esas\cmsgate\bridge\service\MerchantService;
use esas\cmsgate\buynow\hro\admin\AdminBuyNowExceptionPage;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\utils\StringUtils;
use Exception;
use Throwable;

class AdminControllerBuyNow extends Controller
{

    public function process() {
        try {
            $request = $_SERVER['REDIRECT_URL'];
            $controller = null;
            if (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_ADMIN_LOGIN)) {
                $controller = new ControllerBridgeLogin();
            } elseif (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_ADMIN_LOGOUT)) {
                $controller = new ControllerBridgeLogout();
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
            } elseif (StringUtils::contains($request, RedirectServiceBuyNow::PATH_ADMIN_ORDERS)) {
                $controller = new AdminControllerBuyNowOrders();
            } else {
                http_response_code(404);
            }
            $controller->process();
        } catch (Throwable $e) {
            $this->onException();
        } catch (Exception $e) {
            $this->onException();
        }
    }

    public function onException() {
        MerchantService::fromRegistry()->checkAuth(true);
        AdminBuyNowExceptionPage::builder()->buildAndDisplay();
    }
}