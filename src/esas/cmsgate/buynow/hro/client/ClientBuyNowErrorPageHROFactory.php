<?php


namespace esas\cmsgate\buynow\hro\client;


use esas\cmsgate\hro\HROFactory;
use esas\cmsgate\hro\HROManager;

class ClientBuyNowErrorPageHROFactory implements HROFactory
{
    /**
     * @return ClientBuyNowBasketViewHRO
     */
    public static function findBuilder() {
        return HROManager::fromRegistry()->getImplementation(ClientBuyNowErrorPageHRO::class, ClientBuyNowErrorPageHRO_v1::class);
    }
}