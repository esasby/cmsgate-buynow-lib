<?php


namespace esas\cmsgate\buynow\hro\client;


use esas\cmsgate\hro\HROFactory;
use esas\cmsgate\hro\HROManager;

class ClientBuyNowHomeHROFactory implements HROFactory
{
    /**
     * @return ClientBuyNowBasketViewHRO
     */
    public static function findBuilder() {
        return HROManager::fromRegistry()->getImplementation(ClientBuyNowHomeHRO::class, ClientBuyNowHomeHRO_v1::class);
    }
}