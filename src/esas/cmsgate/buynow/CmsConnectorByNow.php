<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 13.04.2020
 * Time: 12:23
 */

namespace esas\cmsgate\buynow;

use esas\cmsgate\bridge\CmsConnectorBridge;
use esas\cmsgate\bridge\service\OrderService;
use esas\cmsgate\buynow\dao\BasketBuyNowRepository;
use esas\cmsgate\buynow\dao\OrderDataBuyNow;
use esas\cmsgate\buynow\lang\LocaleLoaderBuyNow;
use esas\cmsgate\buynow\wrappers\OrderWrapperBuyNow;
use esas\cmsgate\descriptors\CmsConnectorDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\Registry;

class CmsConnectorByNow extends CmsConnectorBridge
{
    /**
     * Для удобства работы в IDE и подсветки синтаксиса.
     * @return $this
     */
    public static function fromRegistry()
    {
        return Registry::getRegistry()->getCmsConnector();
    }

    public function createOrderWrapperCached($cache)
    {
        return new OrderWrapperBuyNow($cache);
    }

    public function createCmsConnectorDescriptor()
    {
        return new CmsConnectorDescriptor(
            "cmsgate-buynow-lib",
            new VersionDescriptor(
                "v2.2.1",
                "2023-05-19"
            ),
            "Cmsgate BuyNow connector",
            "https://github.com/esasby/cmsgate-buynow-lib",
            VendorDescriptor::esas(),
            "buynow"
        );
    }

    public function createLocaleLoaderCached($cache)
    {
        return new LocaleLoaderBuyNow();
    }

    public function getReturnToShopSuccessURL()
    {
        $basket = BasketBuyNowRepository::fromRegistry()->getById(OrderService::fromRegistry()->getSessionOrder()->getBasketId());
        return $basket->getReturnUrl();
    }

    public function getReturnToShopFailedURL()
    {
        $basket = BasketBuyNowRepository::fromRegistry()->getById(OrderService::fromRegistry()->getSessionOrder()->getBasketId());
        return $basket->getReturnUrl();
    }
}