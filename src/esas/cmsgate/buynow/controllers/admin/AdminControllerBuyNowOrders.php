<?php


namespace esas\cmsgate\buynow\controllers\admin;


use esas\cmsgate\bridge\dao\OrderRepository;
use esas\cmsgate\bridge\dao\ShopConfigRepository;
use esas\cmsgate\bridge\service\MerchantService;
use esas\cmsgate\bridge\service\SessionServiceBridge;
use esas\cmsgate\buynow\hro\admin\AdminBuyNowOrderListPage;
use esas\cmsgate\buynow\hro\admin\AdminBuyNowOrderViewPage;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\RequestUtils;
use esas\cmsgate\utils\StringUtils;
use Exception;
use Throwable;

class AdminControllerBuyNowOrders extends Controller
{
    const PATTERN_ORDER_VIEW = '/.*\/orders\/(?<orderId>.+)$/';

    public function process() {
        MerchantService::fromRegistry()->checkAuth(true);
        try {
            $request = RequestUtils::getRequestPath();
            if (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_ADMIN_ORDERS)) {
                $this->renderOrderListPage();
            } elseif (preg_match(self::PATTERN_ORDER_VIEW, $request, $pathParams)) {
                $order = $this->checkOrderPermission($pathParams['orderId']);
                $this->renderOrderViewPage($order);
            } else {
                $this->renderOrderListPage();
            }

        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            throw $e;
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            throw $e;
        }
    }

    public function renderOrderListPage() {
        $orders = array();
        foreach (ShopConfigRepository::fromRegistry()->getByMerchantId(SessionServiceBridge::fromRegistry()->getMerchantUUID()) as $shopConfig) {
            $orders = array_merge($orders, OrderRepository::fromRegistry()->getByShopConfigId($shopConfig->getUuid()));
        }
        AdminBuyNowOrderListPage::builder()
            ->setOrderList($orders)
            ->buildAndDisplay();
        exit(0);
    }

    public function renderOrderViewPage($order) {
        AdminBuyNowOrderViewPage::builder()
            ->setOrder($order)
            ->buildAndDisplay();
        exit(0);
    }

    public function checkOrderPermission($orderId) {
        $order = OrderRepository::fromRegistry()->getByID($orderId);
        $shopConfig = ShopConfigRepository::fromRegistry()->getByID($order->getShopConfigId());
        if ($order == null || $shopConfig->getMerchantId() != SessionServiceBridge::fromRegistry()->getMerchantUUID())
            throw new CMSGateException('This order can not be managed by current merchant');
        return $order;
    }
}