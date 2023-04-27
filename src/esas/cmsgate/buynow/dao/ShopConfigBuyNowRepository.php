<?php
namespace esas\cmsgate\buynow\dao;

use esas\cmsgate\bridge\dao\ShopConfigRepository;
use esas\cmsgate\dao\SingleTableRepository;

abstract class ShopConfigBuyNowRepository extends ShopConfigRepository implements SingleTableRepository
{
    public abstract function getNewOrderId($shopConfigId);

    /**
     * @param $shopConfigId string
     */
    public abstract function deleteById($shopConfigId);

    /**
     * @param $id string
     * @return ShopConfigBuyNow[]
     */
    public abstract function getByMerchantId($merchantId);
}