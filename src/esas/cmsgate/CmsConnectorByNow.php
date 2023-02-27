<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 13.04.2020
 * Time: 12:23
 */

namespace esas\cmsgate;

use esas\cmsgate\descriptors\CmsConnectorDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\lang\LocaleLoaderBuyNow;
use esas\cmsgate\wrappers\OrderWrapperBuyNow;

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
                "v1.18.1",
                "2023-02-27"
            ),
            "Cmsgate BuyNow connector",
            "https://github.com/esasby/cmsgate-buynow-lib",
            VendorDescriptor::esas(),
            "buynow"
        );
    }

    public function createLocaleLoaderCached($cache)
    {
        return new LocaleLoaderBuyNow($cache);
    }

    public function getReturnToShopSuccessURL()
    {
        $cache = BridgeConnector::fromRegistry()->getOrderCacheService()->getSessionOrderCacheSafe();
        return $cache->getOrderData()[RequestParamsTilda::SUCCESS_URL];
    }

    public function getReturnToShopFailedURL()
    {
        $cache = BridgeConnector::fromRegistry()->getOrderCacheService()->getSessionOrderCacheSafe();
        return $cache->getOrderData()[RequestParamsTilda::FAILED_URL];
    }
}