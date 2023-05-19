<?php
namespace esas\cmsgate\buynow\dao;

use esas\cmsgate\dao\Repository;
use esas\cmsgate\Registry;

abstract class ProductBuyNowRepository extends Repository
{
    /**
     * @inheritDoc
     */
    public static function fromRegistry() {
        return Registry::getRegistry()->getService(ProductBuyNowRepository::class);
    }

    /**
     * @param $product ProductBuyNow
     * @return mixed
     */
    public abstract function saveOrUpdate($product);

    /**
     * @param $id string
     * @return ProductBuyNow
     */
    public abstract function getById($productId);

    /**
     * @param $productId string
     */
    public abstract function deleteById($productId);

    /**
     * @param $id string
     * @return ProductBuyNow[]
     */
    public abstract function getByMerchantId($merchantId);


}