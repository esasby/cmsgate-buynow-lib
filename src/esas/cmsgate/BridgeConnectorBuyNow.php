<?php
namespace esas\cmsgate;

use esas\cmsgate\bridge\OrderCacheBuyNowService;
use esas\cmsgate\bridge\OrderCacheRepositoryBuyNow;
use esas\cmsgate\bridge\ShopConfigBuyNowRepository;
use esas\cmsgate\bridge\ShopConfigBuyNowRepositoryPDO;
use esas\cmsgate\buynow\BuyNowBasketItemRepository;
use esas\cmsgate\buynow\BuyNowBasketItemRepositoryPDO;
use esas\cmsgate\buynow\BuyNowBasketRepository;
use esas\cmsgate\buynow\BuyNowBasketRepositoryPDO;
use esas\cmsgate\buynow\BuyNowProductRepository;
use esas\cmsgate\buynow\BuyNowProductRepositoryPDO;
use esas\cmsgate\security\CmsAuthServiceBuyNow;
use esas\cmsgate\security\CryptServiceImpl;
use esas\cmsgate\utils\CMSGateException;


abstract class BridgeConnectorBuyNow extends BridgeConnectorPDO
{
    protected function createCmsAuthService()
    {
        return new CmsAuthServiceBuyNow();
    }

    protected function createCryptService()
    {
        return new CryptServiceImpl('/opt/cmsgate/storage');
    }

    protected function createOrderCacheService() {
        return new OrderCacheBuyNowService();
    }

    /**
     * @return ShopConfigBuyNowRepository
     * @throws CMSGateException
     */
    protected function createShopConfigRepository() {
        return new ShopConfigBuyNowRepositoryPDO($this->getPDO());
    }

    /**
     * @var BuyNowProductRepository
     */
    protected $buyNowProductRepository;

    /**
     * @return BuyNowProductRepository
     */
    public function getBuyNowProductRepository() {
        if ($this->buyNowProductRepository == null)
            $this->buyNowProductRepository = $this->createBuyNowProductRepository();
        return $this->buyNowProductRepository;
    }

    protected function createBuyNowProductRepository() {
        return new BuyNowProductRepositoryPDO($this->getPDO());
    }

    /**
     * @var BuyNowBasketRepository
     */
    protected $buyNowBasketRepository;

    /**
     * @return BuyNowBasketRepository
     */
    public function getBuyNowBasketRepository() {
        if ($this->buyNowBasketRepository == null)
            $this->buyNowBasketRepository = $this->createBuyNowBasketRepository();
        return $this->buyNowBasketRepository;
    }

    protected function createBuyNowBasketRepository() {
        return new BuyNowBasketRepositoryPDO($this->getPDO());
    }

    /**
     * @var BuyNowBasketItemRepository
     */
    protected $buyNowBasketItemRepository;

    /**
     * @return BuyNowBasketItemRepository
     */
    public function getBuyNowBasketItemRepository() {
        if ($this->buyNowBasketItemRepository == null)
            $this->buyNowBasketItemRepository = $this->createBuyNowBasketItemRepository();
        return $this->buyNowBasketItemRepository;
    }

    protected function createBuyNowBasketItemRepository() {
        return new BuyNowBasketItemRepositoryPDO($this->getPDO());
    }

    protected function createOrderCacheRepository() {
        return new OrderCacheRepositoryBuyNow($this->getPDO());
    }
}