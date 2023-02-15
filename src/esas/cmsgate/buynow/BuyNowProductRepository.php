<?php


namespace esas\cmsgate\buynow;


use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\Logger;
use esas\cmsgate\utils\SessionUtils;

abstract class BuyNowProductRepository
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
     * @param $product BuyNowProduct
     * @return mixed
     */
    public abstract function saveOrUpdate($product);

    /**
     * @param $id string
     * @return BuyNowProduct
     */
    public abstract function getById($productId);

    /**
     * @param $productId string
     */
    public abstract function deleteById($productId);

    /**
     * @param $id string
     * @return BuyNowProduct[]
     */
    public abstract function getByMerchantId($merchantId);


}