<?php


namespace esas\cmsgate\buynow\controllers\client;


use esas\cmsgate\buynow\service\OrderCacheBuyNowService;
use esas\cmsgate\buynow\dao\OrderDataBuyNow;
use esas\cmsgate\buynow\dao\OrderDataItemBuyNow;
use esas\cmsgate\buynow\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\hro\client\ClientBuyNowBasketViewHROFactory;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\page\PageUtils;
use esas\cmsgate\utils\RequestUtils;
use esas\cmsgate\bridge\service\SessionServiceBridge;
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
            SessionServiceBridge::fromRegistry()::setShopConfigUUID($basket->getShopConfigId());
            $basketViewPage = ClientBuyNowBasketViewHROFactory::findBuilder()
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
        SessionServiceBridge::fromRegistry()::setShopConfigUUID($basket->getShopConfigId());
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
        RedirectServiceBuyNow::clientOrderView(SessionServiceBridge::fromRegistry()::getOrderCacheUUID(), true);
    }
}