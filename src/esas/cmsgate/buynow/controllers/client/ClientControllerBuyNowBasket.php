<?php


namespace esas\cmsgate\buynow\controllers\client;


use esas\cmsgate\bridge\service\OrderService;
use esas\cmsgate\bridge\service\RecaptchaService;
use esas\cmsgate\bridge\service\SessionServiceBridge;
use esas\cmsgate\buynow\dao\BasketBuyNow;
use esas\cmsgate\buynow\dao\BasketBuyNowRepository;
use esas\cmsgate\buynow\dao\BasketItemBuyNowRepository;
use esas\cmsgate\buynow\dao\OrderBuyNow;
use esas\cmsgate\buynow\dao\OrderDataBuyNow;
use esas\cmsgate\buynow\dao\OrderDataItemBuyNow;
use esas\cmsgate\buynow\hro\client\ClientBuyNowBasketViewHROFactory;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\buynow\service\BasketServiceBuyNow;
use esas\cmsgate\buynow\service\OrderServiceBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\htmlbuilder\page\PageUtils;
use esas\cmsgate\utils\RequestUtils;
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
            $basket = BasketBuyNowRepository::fromRegistry()->getById($this->basketId);
            $isAccessible = BasketServiceBuyNow::fromRegistry()->checkClientPermission($basket);
            SessionServiceBridge::fromRegistry()->setShopConfigUUID($basket->getShopConfigId());
            $basketViewPage = ClientBuyNowBasketViewHROFactory::findBuilder()
                ->setBasket($basket)
                ->setAccessible($isAccessible)
                ->addCssLink($this->getClientUICssLink($basket));
            if (RequestUtils::isMethodPost() && $isAccessible) { // adding or updating
                PageUtils::validateFormInputAndRenderOnError($basketViewPage);
                RecaptchaService::fromRegistry()->validateRequest();
                $this->orderConfirm($basket);
            } else {
                $basketViewPage->render();
                exit(0);
            }
        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            throw $e;
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            throw $e;
        }
    }

    /**
     * @param $basket BasketBuyNow
     * @throws CMSGateException
     */
    protected function orderConfirm($basket) {
        SessionServiceBridge::fromRegistry()->setShopConfigUUID($basket->getShopConfigId());
        $orderDataBuyNow = new OrderDataBuyNow();
        $orderDataBuyNow
            ->setOrderId(OrderServiceBuyNow::fromRegistry()->generateOrderId($basket->getShopConfigId()))
            ->setCustomerFIO(RequestParamsBuyNow::getCustomerFIO())
            ->setCustomerEmail(RequestParamsBuyNow::getCustomerEmail())
            ->setCustomerPhone(RequestParamsBuyNow::getCustomerPhone());
        $amount = 0;
        $basketItems = BasketItemBuyNowRepository::fromRegistry()->getByBasketId(RequestParamsBuyNow::getBasketId());
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
        $order = new OrderBuyNow();
        $order
            ->setBasketId(RequestParamsBuyNow::getBasketId())
            ->setOrderData($orderDataBuyNow)
            ->setExpiresAt(OrderServiceBuyNow::fromRegistry()->getOrderExpirationDate());
        OrderService::fromRegistry()->addSessionOrder($order);
        BasketBuyNowRepository::fromRegistry()->incrementCheckoutCount($basket->getId());
        RedirectServiceBuyNow::fromRegistry()->clientOrderView(SessionServiceBridge::fromRegistry()->getOrderUUID(), true);
    }
}