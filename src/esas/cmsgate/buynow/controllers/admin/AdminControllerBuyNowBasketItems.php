<?php


namespace esas\cmsgate\buynow\controllers\admin;


use esas\cmsgate\bridge\service\MerchantService;
use esas\cmsgate\bridge\service\SessionServiceBridge;
use esas\cmsgate\buynow\dao\BasketItemBuyNow;
use esas\cmsgate\buynow\dao\BasketItemBuyNowRepository;
use esas\cmsgate\buynow\hro\admin\AdminBuyNowBasketItemViewPage;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\buynow\service\BasketServiceBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\htmlbuilder\page\PageUtils;
use esas\cmsgate\utils\RequestUtils;
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
        MerchantService::fromRegistry()->checkAuth(true);
        try {
            $request = RequestUtils::getRequestPath();
            if (preg_match(self::PATTERN_BASKET_ITEM_ADD, $request, $pathParams)) {
                if (RequestUtils::isMethodPost()) { // adding or updating
                    $this->addOrUpdateBasketItem($pathParams['basketId']);
                } else {
                    $basketItem = new BasketItemBuyNow();
                    $basketItem->setBasketId($pathParams['basketId']);
                    $this->renderBasketItemViewPage($basketItem);
                }
            } elseif (preg_match(self::PATTERN_BASKET_ITEM_DELETE, $request, $pathParams)) {
                $basketItem = $this->checkBasketItemPermission($pathParams['basketItemId']);
                BasketItemBuyNowRepository::fromRegistry()->deleteById($basketItem->getId());
                RedirectServiceBuyNow::fromRegistry()->basketEdit($basketItem->getBasketId(), true);
            } elseif (preg_match(self::PATTERN_BASKET_ITEM_EDIT, $request, $pathParams)) {
                $basketItem = $this->checkBasketItemPermission($pathParams['basketItemId']);
                $this->renderBasketItemViewPage($basketItem);
            } else {
                RedirectServiceBuyNow::fromRegistry()->basketList();
            }
        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        }
    }

    public function addOrUpdateBasketItem($basketId) {
        $basketItem = new BasketItemBuyNow();
        $basketItem
            ->setId(RequestParamsBuyNow::getBasketItemId())
            ->setBasketId($basketId)
            ->setProductId(RequestParamsBuyNow::getProductId())
            ->setCount(RequestParamsBuyNow::getBasketItemProductCount())
            ->setMaxCount(RequestParamsBuyNow::getBasketItemProductMaxCount());
        $basketViewPage = AdminBuyNowBasketItemViewPage::builder()
            ->setBasketItem($basketItem);
        PageUtils::validateFormInputAndRenderOnError($basketViewPage);
        try {
            BasketServiceBuyNow::fromRegistry()->checkAdminPermission($basketItem->getBasketId());
            if ($basketItem->getId() != null) {
                self::checkBasketItemPermission($basketItem->getId());
            }
            BasketItemBuyNowRepository::fromRegistry()->saveOrUpdate($basketItem);
            RedirectServiceBuyNow::fromRegistry()->basketEdit($basketItem->getBasketId(), true);
        } catch (Exception $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            $basketViewPage->buildAndDisplay();
            exit(0);
        }
    }

    /**
     * @param $basketItem BasketItemBuyNow
     */
    public function renderBasketItemViewPage($basketItem) {
       AdminBuyNowBasketItemViewPage::builder()
           ->setBasketItem($basketItem)
           ->buildAndDisplay();
        exit(0);
    }

    /**
     * @param $basketItemId
     * @return BasketItemBuyNow
     * @throws CMSGateException
     */
    public static function checkBasketItemPermission($basketItemId) {
        $basketItem = BasketItemBuyNowRepository::fromRegistry()->getById($basketItemId);
        if ($basketItem == null || $basketItem->getBasket()->getShopConfig()->getMerchantId() != SessionServiceBridge::fromRegistry()->getMerchantUUID())
            throw new CMSGateException('This basket can not be managed by current merchant');
        return $basketItem;
    }

}