<?php


namespace esas\cmsgate\buynow\service;


use esas\cmsgate\bridge\service\OrderCacheService;
use esas\cmsgate\buynow\dao\ShopConfigBuyNow;
use esas\cmsgate\buynow\BridgeConnectorBuyNow;
use esas\cmsgate\bridge\service\SessionServiceBridge;
use esas\cmsgate\buynow\dao\ShopConfigBuyNowRepository;

class OrderCacheBuyNowService extends OrderCacheService
{
    /**
     * @return $this
     */
    public static function fromRegistry() {
        return BridgeConnectorBuyNow::fromRegistry()->getOrderCacheService();
    }

    public function loadSessionOrderCacheById($id) {
        parent::loadSessionOrderCacheById($id);
        /** @var ShopConfigBuyNow $shopConfig */
        $shopConfig = BridgeConnectorBuyNow::fromRegistry()->getShopConfigService()->getSessionShopConfig();
        SessionServiceBridge::fromRegistry()::setMerchantUUID($shopConfig->getMerchantId());
    }


    public function generateOrderId($shopConfigId) {
        /** @var ShopConfigBuyNowRepository $shopConfigRepository */
        $shopConfigRepository = BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository();
        return $shopConfigRepository->getNewOrderId($shopConfigId);
    }
}