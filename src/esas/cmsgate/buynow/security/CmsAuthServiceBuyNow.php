<?php


namespace esas\cmsgate\buynow\security;

use esas\cmsgate\bridge\dao\ShopConfig;
use esas\cmsgate\bridge\dao\ShopConfigRepository;
use esas\cmsgate\bridge\security\CmsAuthService;
use esas\cmsgate\buynow\dao\BasketBuyNowRepository;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\CMSGateException;

class CmsAuthServiceBuyNow extends CmsAuthService
{
    public static function fromRegistry() {
        return Registry::getRegistry()->getService(CmsAuthService::class, new CmsAuthServiceBuyNow());
    }

    /**
     * Фактически не выполняет никакой проверки авторизации, а только по идентификатору товара подгружает конфигурацию магазины
     * @param $request
     * @return ShopConfig
     * @throws CMSGateException
     */
    public function checkAuth(&$request)
    {
        $basket = BasketBuyNowRepository::fromRegistry()->getById(RequestParamsBuyNow::getBasketId());
        if (!$basket->isActive())
            throw new CMSGateException('Basket[' . RequestParamsBuyNow::getProductId() . '] is inactive');
        $shopConfig = ShopConfigRepository::fromRegistry()->getById($basket->getShopConfigId());
        return $shopConfig;
    }
}