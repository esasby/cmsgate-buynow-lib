<?php


namespace esas\cmsgate\controllers\admin;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowBasket;
use esas\cmsgate\buynow\BuyNowBasketItem;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\htmlbuilder\page\PageUtils;
use esas\cmsgate\utils\RequestUtils;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\view\admin\AdminBuyNowBasketItemViewPage;
use esas\cmsgate\view\RedirectServiceBuyNow;
use Exception;
use Throwable;

class AdminControllerBuyNowBasketItems extends Controller
{
//    const PATTERN_BASKET_ITEM_EDIT = '/.*\/basket_items\/(?<basketItemId>.+)$/';
//    const PATTERN_BASKET_ITEM_DELETE = '/.*\/basket_items\/(?<basketItemId>.+)\/delete$/';
    const PATTERN_BASKET_ITEM = '/.*\/baskets\/(?<basketId>.+)\/items\/';
    const PATTERN_BASKET_ITEM_ADD = self::PATTERN_BASKET_ITEM . 'add$/';
    const PATTERN_BASKET_ITEM_EDIT = self::PATTERN_BASKET_ITEM .'(?<basketItemId>.+)$/';
    const PATTERN_BASKET_ITEM_DELETE = self::PATTERN_BASKET_ITEM . '(?<basketItemId>.+)\/delete$/';

    public function process() {
        BridgeConnector::fromRegistry()->getMerchantService()->checkAuth(true);
        try {
            $request = RequestUtils::getRequestPath();
            if (preg_match(self::PATTERN_BASKET_ITEM_ADD, $request, $pathParams)) {
                if (RequestUtils::isMethodPost()) { // adding or updating
                    $this->addOrUpdateBasketItem($pathParams['basketId']);
                } else {
                    $basketItem = new BuyNowBasketItem();
                    $basketItem->setBasketId($pathParams['basketId']);
                    $this->renderBasketItemViewPage($basketItem);
                }
            } elseif (preg_match(self::PATTERN_BASKET_ITEM_DELETE, $request, $pathParams)) {
                $basketItem = $this->checkBasketItemPermission($pathParams['basketItemId']);
                BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->deleteById($basketItem->getId());
                RedirectServiceBuyNow::basketEdit($basketItem->getBasketId(), true);
            } elseif (preg_match(self::PATTERN_BASKET_ITEM_EDIT, $request, $pathParams)) {
                $basketItem = $this->checkBasketItemPermission($pathParams['basketItemId']);
                $this->renderBasketItemViewPage($basketItem);
            } else {
                RedirectServiceBuyNow::basketList();
            }
        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        }
    }

    public function addOrUpdateBasketItem($basketId) {
        $basketItem = new BuyNowBasketItem();
        $basketItem
            ->setId(RequestParamsBuyNow::getBasketId())
            ->setBasketId($basketId)
            ->setProductId(RequestParamsBuyNow::getProductId())
            ->setCount(RequestParamsBuyNow::getBasketItemProductCount())
            ->setMaxCount(RequestParamsBuyNow::getBasketItemProductMaxCount());
        $basketViewPage = AdminBuyNowBasketItemViewPage::builder()
            ->setBasketItem($basketItem);
        PageUtils::validateFormInputAndRenderOnError($basketViewPage);
        try {
            AdminControllerBuyNowBaskets::checkBasketPermission($basketItem->getBasketId());
            if ($basketItem->getId() != null) {
                self::checkBasketItemPermission($basketItem->getId());
            }
            BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->saveOrUpdate($basketItem);
            RedirectServiceBuyNow::basketEdit($basketItem->getBasketId(), true);
        } catch (Exception $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            $basketViewPage->buildAndDisplay();
            exit(0);
        }
    }

    /**
     * @param $basketItem BuyNowBasketItem
     */
    public function renderBasketItemViewPage($basketItem) {
       AdminBuyNowBasketItemViewPage::builder()
           ->setBasketItem($basketItem)
           ->buildAndDisplay();
        exit(0);
    }

    /**
     * @param $basketItemId
     * @return BuyNowBasketItem
     * @throws CMSGateException
     */
    public static function checkBasketItemPermission($basketItemId) {
        $basketItem = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->getById($basketItemId);
        if ($basketItem == null || $basketItem->getBasket()->getShopConfig()->getMerchantId() != SessionUtilsBridge::getMerchantUUID())
            throw new CMSGateException('This basket can not be managed by current merchant');
        return $basketItem;
    }

}