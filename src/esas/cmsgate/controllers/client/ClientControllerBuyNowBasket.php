<?php


namespace esas\cmsgate\controllers\client;


use esas\cmsgate\bridge\OrderCacheBuyNowService;
use esas\cmsgate\bridge\OrderDataBuyNow;
use esas\cmsgate\bridge\OrderDataItemBuyNow;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\page\PageUtils;
use esas\cmsgate\utils\RequestUtils;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\view\HROFactoryBuyNow;
use esas\cmsgate\view\RedirectServiceBuyNow;
use Exception;
use Throwable;

class ClientControllerBuyNowBasket extends ClientControllerBuyNow
{
    protected $basketId;

    public function __construct($basketId) {
        parent::__construct();
        $this->basketId = $basketId;
    }

    public function process() {
        try {
            $basket = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getById($this->basketId);
            SessionUtilsBridge::setShopConfigUUID($basket->getShopConfigId());
            $basketViewPage = HROFactoryBuyNow::fromRegistry()->createClientBasketViewPage()
                ->setBasket($basket)
                ->addCssLink($this->getClientUICssLink($basket));
            if (RequestUtils::isMethodPost()) { // adding or updating
                PageUtils::validateFormInputAndRenderOnError($basketViewPage);
                $this->orderConfirm();
            } else {
                $basketViewPage->render();
                exit(0);
            }

        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        }
    }

    protected function orderConfirm() {
        $basket = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getById(RequestParamsBuyNow::getBasketId());
        SessionUtilsBridge::setShopConfigUUID($basket->getShopConfigId());
        $orderDataBuyNow = new OrderDataBuyNow();
        $orderDataBuyNow
            ->setOrderId(OrderCacheBuyNowService::fromRegistry()->generateOrderId($basket->getShopConfigId()))
            ->setBasketId(RequestParamsBuyNow::getBasketId())
            ->setCustomerFIO(RequestParamsBuyNow::getCustomerFIO())
            ->setCustomerEmail(RequestParamsBuyNow::getCustomerEmail())
            ->setCustomerPhone(RequestParamsBuyNow::getCustomerPhone());
        $amount = 0;
        $basketItems = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->getByBasketId(RequestParamsBuyNow::getBasketId());
        foreach ($basketItems as $basketItem) {
            $product = $basketItem->getProduct();
            $count = RequestParamsBuyNow::getBasketProductCount($basketItem->getProductId());
            if ($count <= 0)
                continue;
            $orderItem = new OrderDataItemBuyNow();
            $orderItem
                ->setProductId($product->getId())
                ->setPrice($product->getPrice())
                ->setSku($product->getSku())
                ->setName($product->getName())
                ->setCount($count);
            $orderDataBuyNow->addItem($orderItem);
            $amount += $orderItem->getPrice() * $orderItem->getCount();
        }
        $orderDataBuyNow->setAmount($amount);
        BridgeConnectorBuyNow::fromRegistry()->getOrderCacheService()->addSessionOrderCache($orderDataBuyNow);
        BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->incrementCheckoutCount($basket->getId());
        RedirectServiceBuyNow::clientOrderView(SessionUtilsBridge::getOrderCacheUUID(), true);
    }
}