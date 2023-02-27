<?php


namespace esas\cmsgate\bridge;


use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\view\admin\AdminBuyNowShopConfigViewPage;
use esas\cmsgate\view\RedirectServiceBuyNow;

abstract class MerchantServiceBuyNow extends MerchantService
{
    public function addOrUpdateAuth($login, $password, $hash) {
        return BridgeConnectorBuyNow::fromRegistry()->getBuyNowMerchantRepository()->addOrUpdateAuth($login, $password, $hash);
    }

    public function getAuthHashById($id) {
        return BridgeConnectorBuyNow::fromRegistry()->getBuyNowMerchantRepository()->getAuthHashById($id);
    }

    public function createAdminConfigPage() {
        return new AdminBuyNowShopConfigViewPage();
    }

    public function isSingleShopConfigMode() {
        return false;
    }

    public function createRedirectService() {
        return new RedirectServiceBuyNow();
    }


}