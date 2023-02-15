<?php


namespace esas\cmsgate\bridge;


use esas\cmsgate\BridgeConnectorBuyNow;

class MerchantServiceBuyNow extends MerchantService
{
    public function addOrUpdateAuth($login, $password, $hash) {
        BridgeConnectorBuyNow::fromRegistry()->getBuyNowMerchantRepository()->addOrUpdateAuth($login, $password, $hash);
    }

    public function getAuthHashById($id) {
        BridgeConnectorBuyNow::fromRegistry()->getBuyNowMerchantRepository()->getAuthHashById($id);
    }

    public function createAdminLoginPage() {
        // TODO: Implement createAdminLoginPage() method.
    }

    public function createAdminConfigPage() {
        // TODO: Implement createAdminConfigPage() method.
    }

    public function isSingleShopConfigMode() {
        return false;
    }


}