<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 13.04.2020
 * Time: 12:23
 */

namespace esas\cmsgate\buynow;

use esas\cmsgate\bridge\CmsConnectorBridge;
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
                "v2.0.0",
                "2023-04-27"
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
        /** @var OrderDataBuyNow $orderData */
        $orderData = BridgeConnectorBuyNow::fromRegistry()->getOrderCacheService()->getSessionOrderCache()->getOrderData();
        $basket = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getById($orderData->getBasketId());
        return $basket->getReturnUrl();
    }

    public function getReturnToShopFailedURL()
    {
        /** @var OrderDataBuyNow $orderData */
        $orderData = BridgeConnectorBuyNow::fromRegistry()->getOrderCacheService()->getSessionOrderCache()->getOrderData();
        $basket = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getById($orderData->getBasketId());
        return $basket->getReturnUrl();
    }
}