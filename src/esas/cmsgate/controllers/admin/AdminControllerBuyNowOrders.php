<?php


namespace esas\cmsgate\controllers\admin;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\RequestUtils;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\utils\StringUtils;
use esas\cmsgate\view\admin\AdminBuyNowOrderListPage;
use esas\cmsgate\view\admin\AdminBuyNowOrderViewPage;
use esas\cmsgate\view\RedirectServiceBuyNow;
use Exception;
use Throwable;

class AdminControllerBuyNowOrders extends Controller
{
    const PATTERN_ORDER_VIEW = '/.*\/orders\/(?<orderId>.+)$/';

    public function process() {
        BridgeConnector::fromRegistry()->getMerchantService()->checkAuth(true);
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
        foreach (BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository()->getByMerchantId(SessionUtilsBridge::getMerchantUUID()) as $shopConfig) {
            $orders = array_merge($orders, BridgeConnectorBuyNow::fromRegistry()->getOrderCacheRepository()->getByShopConfigId($shopConfig->getUuid()));
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
        $order = BridgeConnectorBuyNow::fromRegistry()->getOrderCacheRepository()->getByUUID($orderId);
        $shopConfig = BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository()->getByUUID($order->getShopConfigId());
        if ($order == null || $shopConfig->getMerchantId() != SessionUtilsBridge::getMerchantUUID())
            throw new CMSGateException('This order can not be managed by current merchant');
        return $order;
    }
}