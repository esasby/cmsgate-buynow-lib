<?php


namespace esas\cmsgate\controllers\admin;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowBasket;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\htmlbuilder\page\PageUtils;
use esas\cmsgate\utils\RequestUtils;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\utils\StringUtils;
use esas\cmsgate\view\admin\AdminBuyNowBasketListPage;
use esas\cmsgate\view\admin\AdminBuyNowBasketViewPage;
use esas\cmsgate\view\RedirectServiceBuyNow;
use Exception;
use Throwable;

class AdminControllerBuyNowBaskets extends Controller
{
    const PATTERN_BASKET_EDIT = '/.*\/baskets\/(?<basketId>.+)$/';
    const PATTERN_BASKET_DELETE = '/.*\/baskets\/(?<basketId>.+)\/delete$/';

    public function process() {
        BridgeConnector::fromRegistry()->getMerchantService()->checkAuth(true);
        try {
            $request = RequestUtils::getRequestPath();
            if (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_ADMIN_BASKETS)) {
                if (RequestUtils::isMethodPost()) { // adding or updating
                    $this->addOrUpdateBasket();
                }
                $this->renderBasketListPage();
            } elseif (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_ADMIN_BASKETS_ADD)) {
                $basket = new BuyNowBasket();
                $this->renderBasketViewPage($basket);
            } elseif (preg_match(self::PATTERN_BASKET_DELETE, $request, $pathParams)) {
                $basket = $this->checkBasketPermission($pathParams['basketId']);
                BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->deleteByBasketId($basket->getId());
                BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->deleteById($basket->getId());
                $this->renderBasketListPage();
            } elseif (preg_match(self::PATTERN_BASKET_EDIT, $request, $pathParams)) {
                $basket = $this->checkBasketPermission($pathParams['basketId']);
                $this->renderBasketViewPage($basket);
            } else {
                $this->renderBasketListPage();
            }

        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        }
    }

    public function addOrUpdateBasket() {
        $basket = new BuyNowBasket();
        $basket
            ->setId(RequestParamsBuyNow::getBasketId())
            ->setShopConfigId(RequestParamsBuyNow::getBasketShopConfigId())
            ->setName(RequestParamsBuyNow::getBasketName())
            ->setDescription(RequestParamsBuyNow::getBasketDescription())
            ->setActive(RequestParamsBuyNow::getBasketActive())
            ->setAskFIO(RequestParamsBuyNow::getBasketAskName())
            ->setAskEmail(RequestParamsBuyNow::getBasketAskEmail())
            ->setAskPhone(RequestParamsBuyNow::getBasketAskPhone())
        ;
        $basketViewPage = new AdminBuyNowBasketViewPage($basket);
        PageUtils::validateFormInputAndRenderOnError($basketViewPage);
        try {
            AdminControllerBuyNowShopConfigs::checkShopConfigPermission($basket->getShopConfigId());
            if ($basket->getId() != null) {
                self::checkBasketPermission($basket->getId());
            }
            BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->saveOrUpdate($basket);
            $this->renderBasketListPage();
        } catch (Exception $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            $basketViewPage->render();
            exit(0);
        }
    }

    public function renderBasketListPage() {
        (new AdminBuyNowBasketListPage())->render();
        exit(0);
    }

    public function renderBasketViewPage($basket) {
        (new AdminBuyNowBasketViewPage($basket))->render();
        exit(0);
    }

    public static function checkBasketPermission($basketId) {
        $basket = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getById($basketId);
        if ($basket == null || $basket->getShopConfig()->getMerchantId() != SessionUtilsBridge::getMerchantUUID())
            throw new CMSGateException('This basket can not be managed by current merchant');
        return $basket;
    }

}