<?php
namespace esas\cmsgate\buynow\dao;

use esas\cmsgate\dao\Repository;
use esas\cmsgate\dao\SingleTableRepository;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\Logger;

abstract class BasketItemBuyNowRepository extends Repository implements SingleTableRepository
{
    /**
     * @inheritDoc
     */
    public static function fromRegistry() {
        return Registry::getRegistry()->getService(BasketItemBuyNowRepository::class);
    }

    /**
     * @param $basketItem BasketItemBuyNow
     * @return mixed
     */
    public abstract function saveOrUpdate($basketItem);

    /**
     * @param $basketItemId string
     * @return BasketItemBuyNow
     */
    public abstract function getById($basketItemId);

    /**
     * @param $basketItemId string
     */
    public abstract function deleteById($basketItemId);

    /**
     * @param $basketProductId string
     */
    public abstract function deleteByProductId($basketProductId);

    /**
     * @param $basketId string
     */
    public abstract function deleteByBasketId($basketId);


    /**
     * @param $basketId string
     * @return BasketItemBuyNow[]
     */
    public abstract function getByBasketId($basketId);

    public function getByBasketIdOnlyActiveProducts($basketId) {
        $ret = array();
        $items = $this->getByBasketId($basketId);
        foreach ($items as $item) {
            if ($item->getProduct()->isActive())
                $ret[] = $item;
        }
        return $ret;
    }

}