<?php


namespace esas\cmsgate\buynow\security;


use esas\cmsgate\bridge\BridgeConnector;
use esas\cmsgate\bridge\dao\ShopConfig;
use esas\cmsgate\buynow\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\bridge\security\CmsAuthService;
use esas\cmsgate\utils\CMSGateException;

class CmsAuthServiceBuyNow extends CmsAuthService
{
    /**
     * Фактически не выполняет никакой проверки авторизации, а только по идентификатору товара подгружает конфигурацию магазины
     * @param $request
     * @return ShopConfig
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