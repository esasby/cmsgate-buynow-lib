<?php


namespace esas\cmsgate\buynow\service;


use esas\cmsgate\bridge\service\MerchantService;
use esas\cmsgate\buynow\BridgeConnectorBuyNow;

class MerchantServiceBuyNow extends MerchantService
{
    public function addOrUpdateAuth($login, $password, $hash) {
        return BridgeConnectorBuyNow::fromRegistry()->getMerchantRepository()->addOrUpdateAuth($login, $password, $hash);
    }

    public function getAuthHashById($id) {
        return BridgeConnectorBuyNow::fromRegistry()->getMerchantRepository()->getAuthHashById($id);
    }

    public function isSingleShopConfigMode() {
        return false;
    }

    public function createRedirectService() {
        return new RedirectServiceBuyNow();
    }


}