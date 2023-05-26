<?php


namespace esas\cmsgate\buynow\service;


use esas\cmsgate\bridge\dao\OrderRepository;
use esas\cmsgate\bridge\dao\ShopConfigRepository;
use esas\cmsgate\bridge\security\CmsAuthService;
use esas\cmsgate\bridge\security\CryptServiceImpl;
use esas\cmsgate\bridge\service\BridgeServiceProvider;
use esas\cmsgate\bridge\service\MerchantService;
use esas\cmsgate\bridge\service\OrderService;
use esas\cmsgate\bridge\service\ServiceProviderBridge;
use esas\cmsgate\buynow\dao\BasketBuyNowRepository;
use esas\cmsgate\buynow\dao\BasketBuyNowRepositoryPDO;
use esas\cmsgate\buynow\dao\BasketItemBuyNowRepository;
use esas\cmsgate\buynow\dao\BasketItemBuyNowRepositoryPDO;
use esas\cmsgate\buynow\dao\OrderRepositoryBuyNow;
use esas\cmsgate\buynow\dao\ProductBuyNowRepository;
use esas\cmsgate\buynow\dao\ProductBuyNowRepositoryPDO;
use esas\cmsgate\buynow\dao\ShopConfigBuyNowRepositoryPDO;
use esas\cmsgate\buynow\security\CmsAuthServiceBuyNow;
use esas\cmsgate\service\RedirectService;

class ServiceProviderBuyNow extends ServiceProviderBridge
{
    public function getServiceArray() {
        $services = parent::getServiceArray();
        $services[CmsAuthService::class] = new CmsAuthServiceBuyNow();
        $services[CryptServiceImpl::class] = new CryptServiceImpl();
        $services[OrderService::class] = new OrderServiceBuyNow();
        $services[MerchantService::class] = new MerchantServiceBuyNow();
        $services[RedirectService::class] = new RedirectServiceBuyNow();
        //repositories
        $services[ShopConfigRepository::class] = new ShopConfigBuyNowRepositoryPDO();
        $services[OrderRepository::class] = new OrderRepositoryBuyNow();
        $services[BasketBuyNowRepository::class] = new BasketBuyNowRepositoryPDO();
        $services[BasketItemBuyNowRepository::class] = new BasketItemBuyNowRepositoryPDO();
        $services[ProductBuyNowRepository::class] = new ProductBuyNowRepositoryPDO();
        return $services;
    }
}