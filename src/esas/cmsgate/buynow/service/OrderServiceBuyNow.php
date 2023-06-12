<?php


namespace esas\cmsgate\buynow\service;


use DateTime;
use esas\cmsgate\bridge\dao\ShopConfigRepository;
use esas\cmsgate\bridge\service\OrderService;
use esas\cmsgate\bridge\service\SessionServiceBridge;
use esas\cmsgate\bridge\service\ShopConfigService;
use esas\cmsgate\buynow\dao\ShopConfigBuyNow;
use esas\cmsgate\buynow\dao\ShopConfigBuyNowRepository;
use esas\cmsgate\Registry;

class OrderServiceBuyNow extends OrderService
{
    /**
     * @inheritDoc
     */
    public static function fromRegistry() {
        return Registry::getRegistry()->getService(OrderService::class, new OrderServiceBuyNow());
    }

    public function loadSessionOrderById($id) {
        parent::loadSessionOrderById($id);
        /** @var ShopConfigBuyNow $shopConfig */
        $shopConfig = ShopConfigService::fromRegistry()->getSessionShopConfig();
        SessionServiceBridge::fromRegistry()->setMerchantUUID($shopConfig->getMerchantId());
    }


    public function generateOrderId($shopConfigId) {
        /** @var ShopConfigBuyNowRepository $shopConfigRepository */
        $shopConfigRepository = ShopConfigRepository::fromRegistry();
        return $shopConfigRepository->getNewOrderId($shopConfigId);
    }

    /**
     * @return DateTime|null
     */
    public function getOrderExpirationDate() {
        return null; //npo expiration date
    }
}