<?php


namespace esas\cmsgate\buynow;


use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\Logger;
use esas\cmsgate\utils\SessionUtils;

abstract class BuyNowBasketItemRepository
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

}