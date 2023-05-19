<?php


namespace esas\cmsgate\buynow\dao;


use esas\cmsgate\dao\Repository;
use esas\cmsgate\dao\SingleTableRepository;
use esas\cmsgate\Registry;

abstract class BasketBuyNowRepository extends Repository implements SingleTableRepository
{
    /**
     * @inheritDoc
     */
    public static function fromRegistry() {
        return Registry::getRegistry()->getService(BasketBuyNowRepository::class);
    }

    /**
     * @param $basket BasketBuyNow
     * @return mixed
     */
    public abstract function saveOrUpdate($basket);

    /**
     * @param $basketId string
     * @return BasketBuyNow
     */
    public abstract function getById($basketId);

    /**
     * @param $productId string
     * @return BasketBuyNow[]
     */
    public abstract function getByProductId($productId);

    /**
     * @param $shopConfigId string
     * @return BasketBuyNow[]
     */
    public abstract function getByShopConfigId($shopConfigId);

    public abstract function incrementCheckoutCount($basketId);

    /**
     * @param $id string
     * @return BasketBuyNow[]
     */
    public abstract function getByMerchantId($merchantId);

    /**
     * @param $basketId string
     */
    public abstract function deleteById($basketId);


}