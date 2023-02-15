<?php


namespace esas\cmsgate\buynow;


use esas\cmsgate\utils\Logger;

abstract class BuyNowMerchantRepository
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

    public abstract function addOrUpdateAuth($login, $password, $hash);

    public abstract function getAuthHashById($id);
}