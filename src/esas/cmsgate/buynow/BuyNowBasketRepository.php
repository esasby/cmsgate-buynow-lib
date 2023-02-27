<?php


namespace esas\cmsgate\buynow;


use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\Logger;
use esas\cmsgate\utils\SessionUtils;

abstract class BuyNowBasketRepository
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * BuyNowBasketRepository constructor.
     */
    public function __construct()
    {
        $this->logger = Logger::getLogger(get_class($this));

    }

    /**
     * @param $basket BuyNowBasket
     * @return mixed
     */
    public abstract function saveOrUpdate($basket);

    /**
     * @param $basketId string
     * @return BuyNowBasket
     */
    public abstract function getById($basketId);

    /**
     * @param $shopConfigId string
     * @return BuyNowBasket[]
     */
    public abstract function getByShopConfigId($shopConfigId);

    public abstract function incrementCheckoutCount($basketId);

    /**
     * @param $id string
     * @return BuyNowBasket[]
     */
    public abstract function getByMerchantId($merchantId);

    /**
     * @param $basketId string
     */
    public abstract function deleteById($basketId);


}