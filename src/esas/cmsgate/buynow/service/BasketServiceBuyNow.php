<?php


namespace esas\cmsgate\buynow\service;


use esas\cmsgate\bridge\service\SessionServiceBridge;
use esas\cmsgate\buynow\controllers\admin\AdminControllerBuyNowProducts;
use esas\cmsgate\buynow\dao\BasketBuyNow;
use esas\cmsgate\buynow\dao\BasketBuyNowRepository;
use esas\cmsgate\buynow\dao\BasketItemBuyNowRepository;
use esas\cmsgate\buynow\dao\OrderRepositoryBuyNow;
use esas\cmsgate\buynow\messenger\MessagesBuyNow;
use esas\cmsgate\messenger\Messenger;
use esas\cmsgate\Registry;
use esas\cmsgate\service\Service;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\Logger;

class BasketServiceBuyNow extends Service
{
    public static function fromRegistry() {
        return Registry::getRegistry()->getService(BasketServiceBuyNow::class, new BasketServiceBuyNow());
    }

    /**
     * @param $basket BasketBuyNow
     * @return boolean
     * @throws CMSGateException
     */
    public function checkClientPermission($basket) {
        if ($basket == null) {
            throw new CMSGateException(MessagesBuyNow::BASKET_INCORRECT_ID);
        } elseif (!$basket->isActive()) {
            Messenger::fromRegistry()->addErrorMessage(MessagesBuyNow::BASKET_IS_INACTIVE);
            return false;
        } elseif (!empty($basket->getExpiresAt()) && $basket->getExpiresAt()->getTimestamp() < strtotime("now")) {
            Messenger::fromRegistry()->addErrorMessage(MessagesBuyNow::BASKET_IS_EXPIRED);
            return false;
        }
        $payedAndNotExpiresCount = OrderRepositoryBuyNow::fromRegistry()->countByBasketId($basket->getId());
        if ($basket->getPaidMaxCount() != 0 && $payedAndNotExpiresCount >= $basket->getPaidMaxCount()){
            Messenger::fromRegistry()->addErrorMessage(MessagesBuyNow::BASKET_LIMIT_REACHED);
            return false;
        }
        return true;
    }

    public function checkAdminPermission($basketId) {
        $basket = BasketBuyNowRepository::fromRegistry()->getById($basketId);
        if ($basket == null || $basket->getShopConfig()->getMerchantId() != SessionServiceBridge::fromRegistry()->getMerchantUUID())
            throw new CMSGateException('This basket can not be managed by current merchant');
        return $basket;
    }

    public function getBasketItemList($basket) {
        $basketItems = array();
        if ($basket->getId() != null) {
            foreach (BasketItemBuyNowRepository::fromRegistry()->getByBasketId($basket->getId()) as $item) {
                try {
                    AdminControllerBuyNowProducts::checkProductPermission($item->getProductId());
                    $basketItems[] = $item;
                } catch (CMSGateException $e) {
                    Logger::getLogger('BasketsController')->error("Error: ", $e);
                    BasketItemBuyNowRepository::fromRegistry()->deleteById($item->getId());
                    Logger::getLogger('BasketsController')->info('Basket item[' . $item->getId() . '] was deleted');

                }
            }
        }
        return $basketItems;
    }
}