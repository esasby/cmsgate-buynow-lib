<?php


namespace esas\cmsgate\security;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\utils\CMSGateException;

class CmsAuthServiceBuyNow extends CmsAuthService
{
    /**
     * Фактически не выполняет никакой проверки авторизации, а только по идентификатору товара подгружает конфигурацию магазины
     * @param $request
     * @return \esas\cmsgate\bridge\ShopConfig
     * @throws CMSGateException
     */
    public function checkAuth(&$request)
    {
        $basket = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getById(RequestParamsBuyNow::getBasketId());
        if (!$basket->isActive())
            throw new CMSGateException('Basket[' . RequestParamsBuyNow::getProductId() . '] is inactive');
        $shopConfig = BridgeConnector::fromRegistry()->getShopConfigRepository()->getById($basket->getShopConfigId());
        return $shopConfig;
    }
}