<?php


namespace esas\cmsgate\buynow;


use esas\cmsgate\dao\SingleTableRepository;
use esas\cmsgate\utils\Logger;

abstract class BuyNowBasketItemRepository implements SingleTableRepository
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * BuyNowProductRepository constructor.
     */
    public function __construct()
    {
        $this->logger = Logger::getLogger(get_class($this));

    }

    /**
     * @param $basketItem BuyNowBasketItem
     * @return mixed
     */
    public abstract function saveOrUpdate($basketItem);

    /**
     * @param $basketItemId string
     * @return BuyNowBasketItem
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
     * @return BuyNowBasketItem[]
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