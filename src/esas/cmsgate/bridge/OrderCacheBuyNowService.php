<?php


namespace esas\cmsgate\bridge;


use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\protocol\RequestParamsBuyNow;

class OrderCacheBuyNowService extends OrderCacheService
{
    public function addSessionOrderCache($orderData) {
        $basket = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getById(RequestParamsBuyNow::getBasketId());
        $orderDataBuyNow = new OrderDataBuyNow();
        $orderDataBuyNow->setOrderId($this->generateOrderId($basket->getShopConfigId()));
        $orderDataBuyNow->setBasketId(RequestParamsBuyNow::getBasketId());
        $orderDataBuyNow->setCustomerFIO(RequestParamsBuyNow::getCustomerFIO());
        $orderDataBuyNow->setCustomerEmail(RequestParamsBuyNow::getCustomerEmail());
        $orderDataBuyNow->setCustomerPhone(RequestParamsBuyNow::getCustomerPhone());
        $amount = 0;
        foreach ($basket->getItems() as $basketItem) {
            $product = $basketItem->getProduct();
            $orderItem = new OrderDataItemBuyNow();
            $orderItem->setPrice($product->getPrice());
            $orderItem->setSku($product->getSku());
            $orderItem->setName($product->getName());
            $orderItem->setCount(RequestParamsBuyNow::getBasketItems()[$basketItem->getProductId()]);
            $amount += $orderItem->getPrice() * $orderItem->getCount();
        }
        $orderDataBuyNow->setAmount($amount);
        parent::addSessionOrderCache($orderDataBuyNow);
    }

    public function generateOrderId($shopConfigId) {
        /** @var ShopConfigBuyNowRepository $shopConfigRepository */
        $shopConfigRepository = BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository();
        return $shopConfigRepository->getNewOrderId($shopConfigId);
    }
}