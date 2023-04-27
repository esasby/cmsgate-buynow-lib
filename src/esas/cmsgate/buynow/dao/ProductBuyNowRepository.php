<?php
namespace esas\cmsgate\buynow\dao;

use esas\cmsgate\utils\Logger;

abstract class ProductBuyNowRepository
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * ProductBuyNowRepository constructor.
     */
    public function __construct()
    {
        $this->logger = Logger::getLogger(get_class($this));

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