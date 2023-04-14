<?php


namespace esas\cmsgate\bridge;


use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\view\RedirectServiceBuyNow;

abstract class MerchantServiceBuyNow extends MerchantService
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