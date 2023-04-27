<?php
namespace esas\cmsgate\buynow;

use esas\cmsgate\bridge\BridgeConnectorPDO;
use esas\cmsgate\buynow\dao\BasketItemBuyNowRepository;
use esas\cmsgate\buynow\dao\BasketItemBuyNowRepositoryPDO;
use esas\cmsgate\buynow\dao\BasketBuyNowRepository;
use esas\cmsgate\buynow\dao\BasketBuyNowRepositoryPDO;
use esas\cmsgate\buynow\dao\OrderCacheRepositoryBuyNow;
use esas\cmsgate\buynow\dao\ProductBuyNowRepository;
use esas\cmsgate\buynow\dao\ProductBuyNowRepositoryPDO;
use esas\cmsgate\buynow\dao\ShopConfigBuyNowRepository;
use esas\cmsgate\buynow\dao\ShopConfigBuyNowRepositoryPDO;
use esas\cmsgate\buynow\security\CmsAuthServiceBuyNow;
use esas\cmsgate\buynow\service\OrderCacheBuyNowService;
use esas\cmsgate\bridge\security\CryptServiceImpl;
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
     * @var ProductBuyNowRepository
     */
    protected $buyNowProductRepository;

    /**
     * @return ProductBuyNowRepository
     */
    public function getBuyNowProductRepository() {
        if ($this->buyNowProductRepository == null)
            $this->buyNowProductRepository = $this->createBuyNowProductRepository();
        return $this->buyNowProductRepository;
    }

    protected function createBuyNowProductRepository() {
        return new ProductBuyNowRepositoryPDO($this->getPDO());
    }

    /**
     * @var BasketBuyNowRepository
     */
    protected $buyNowBasketRepository;

    /**
     * @return BasketBuyNowRepository
     */
    public function getBuyNowBasketRepository() {
        if ($this->buyNowBasketRepository == null)
            $this->buyNowBasketRepository = $this->createBuyNowBasketRepository();
        return $this->buyNowBasketRepository;
    }

    protected function createBuyNowBasketRepository() {
        return new BasketBuyNowRepositoryPDO($this->getPDO());
    }

    /**
     * @var BasketItemBuyNowRepository
     */
    protected $buyNowBasketItemRepository;

    /**
     * @return BasketItemBuyNowRepository
     */
    public function getBuyNowBasketItemRepository() {
        if ($this->buyNowBasketItemRepository == null)
            $this->buyNowBasketItemRepository = $this->createBuyNowBasketItemRepository();
        return $this->buyNowBasketItemRepository;
    }

    protected function createBuyNowBasketItemRepository() {
        return new BasketItemBuyNowRepositoryPDO($this->getPDO());
    }

    protected function createOrderCacheRepository() {
        return new OrderCacheRepositoryBuyNow($this->getPDO());
    }
}