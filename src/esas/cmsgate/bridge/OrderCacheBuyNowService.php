<?php


namespace esas\cmsgate\bridge;


use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\utils\SessionUtilsBridge;

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
        SessionUtilsBridge::setMerchantUUID($shopConfig->getMerchantId());
    }


    public function generateOrderId($shopConfigId) {
        /** @var ShopConfigBuyNowRepository $shopConfigRepository */
        $shopConfigRepository = BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository();
        return $shopConfigRepository->getNewOrderId($shopConfigId);
    }
}