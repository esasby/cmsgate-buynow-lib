<?php


namespace esas\cmsgate\buynow\service;


use esas\cmsgate\bridge\dao\MerchantRepository;
use esas\cmsgate\bridge\service\MerchantService;
use esas\cmsgate\Registry;

class MerchantServiceBuyNow extends MerchantService
{
    public static function fromRegistry() {
        return Registry::getRegistry()->getService(MerchantService::class, new MerchantServiceBuyNow());
    }

    public function addOrUpdateAuth($login, $password, $hash) {
        return MerchantRepository::fromRegistry()->addOrUpdateAuth($login, $password, $hash);
    }

    public function getAuthHashById($id) {
        return MerchantRepository::fromRegistry()->getAuthHashById($id);
    }

    public function isSingleShopConfigMode() {
        return false;
    }
}