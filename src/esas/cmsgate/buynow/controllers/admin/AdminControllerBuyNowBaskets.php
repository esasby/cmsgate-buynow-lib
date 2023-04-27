<?php


namespace esas\cmsgate\buynow\controllers\admin;


use esas\cmsgate\bridge\BridgeConnector;
use esas\cmsgate\bridge\service\SessionServiceBridge;
use esas\cmsgate\buynow\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\dao\BasketBuyNow;
use esas\cmsgate\buynow\hro\admin\AdminBuyNowBasketListPage;
use esas\cmsgate\buynow\hro\admin\AdminBuyNowBasketViewPage;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\htmlbuilder\page\PageUtils;
use esas\cmsgate\utils\Logger;
use esas\cmsgate\utils\RequestUtils;
use esas\cmsgate\utils\StringUtils;
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
                $basket = new BasketBuyNow();
                $this->renderBasketViewPage($basket);
            } elseif (preg_match(self::PATTERN_BASKET_DELETE, $request, $pathParams)) {
                $basket = $this->checkBasketPermission($pathParams['basketId']);
                BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->deleteByBasketId($basket->getId());
                BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->deleteById($basket->getId());
                RedirectServiceBuyNow::basketList(true);
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
        $basket = new BasketBuyNow();
        $basket
            ->setId(RequestParamsBuyNow::getBasketId())
            ->setShopConfigId(RequestParamsBuyNow::getBasketShopConfigId())
            ->setName(RequestParamsBuyNow::getBasketName())
            ->setDescription(RequestParamsBuyNow::getBasketDescription())
            ->setActive(RequestParamsBuyNow::getBasketActive())
            ->setAskFIO(RequestParamsBuyNow::getBasketAskName())
            ->setAskEmail(RequestParamsBuyNow::getBasketAskEmail())
            ->setAskPhone(RequestParamsBuyNow::getBasketAskPhone())
            ->setReturnUrl(RequestParamsBuyNow::getBasketReturnUrl())
            ->setClientUICss(RequestParamsBuyNow::getClientUICss())
        ;
        $basketViewPage = AdminBuyNowBasketViewPage::builder();
        PageUtils::validateFormInputAndRenderOnError($basketViewPage);
        try {
            AdminControllerBuyNowShopConfigs::checkShopConfigPermission($basket->getShopConfigId());
            if ($basket->getId() != null) {
                self::checkBasketPermission($basket->getId());
            }
            BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->saveOrUpdate($basket);
            RedirectServiceBuyNow::basketList(true);
        } catch (Exception $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            $basketViewPage->render();
            exit(0);
        }
    }

    public function renderBasketListPage() {
        AdminBuyNowBasketListPage::builder()
            ->setBasketList(BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getByMerchantId(SessionServiceBridge::fromRegistry()::getMerchantUUID()))
            ->buildAndDisplay();
        exit(0);
    }

    public function renderBasketViewPage($basket) {

        AdminBuyNowBasketViewPage::builder()
            ->setBasket($basket)
            ->setBasketItems(self::getBasketItemList($basket))
            ->buildAndDisplay();
        exit(0);
    }

    public static function getBasketItemList($basket) {
        $basketItems = array();
        if ($basket->getId() != null) {
            foreach (BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->getByBasketId($basket->getId()) as $item) {
                try {
                    AdminControllerBuyNowProducts::checkProductPermission($item->getProductId());
                    $basketItems[] = $item;
                } catch (CMSGateException $e) {
                    Logger::getLogger('BasketsController')->error("Error: ", $e);
                    BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->deleteById($item->getId());
                    Logger::getLogger('BasketsController')->info('Basket item[' . $item->getId() . '] was deleted');

                }
            }
        }
        return $basketItems;
    }

    public static function checkBasketPermission($basketId) {
        $basket = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getById($basketId);
        if ($basket == null || $basket->getShopConfig()->getMerchantId() != SessionServiceBridge::fromRegistry()::getMerchantUUID())
            throw new CMSGateException('This basket can not be managed by current merchant');
        return $basket;
    }

}