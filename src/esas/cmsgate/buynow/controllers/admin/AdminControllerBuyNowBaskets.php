<?php


namespace esas\cmsgate\buynow\controllers\admin;


use DateTime;
use esas\cmsgate\bridge\service\MerchantService;
use esas\cmsgate\bridge\service\SessionServiceBridge;

use esas\cmsgate\buynow\dao\BasketBuyNow;
use esas\cmsgate\buynow\dao\BasketBuyNowRepository;
use esas\cmsgate\buynow\dao\BasketItemBuyNowRepository;
use esas\cmsgate\buynow\hro\admin\AdminBuyNowBasketListPage;
use esas\cmsgate\buynow\hro\admin\AdminBuyNowBasketViewPage;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\buynow\service\BasketServiceBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\page\PageUtils;
use esas\cmsgate\utils\RequestUtils;
use esas\cmsgate\utils\StringUtils;
use esas\cmsgate\view\admin\validators\ValidatorDateTimeLocal;
use Exception;
use Throwable;

class AdminControllerBuyNowBaskets extends Controller
{
    const PATTERN_BASKET_EDIT = '/.*\/baskets\/(?<basketId>.+)$/';
    const PATTERN_BASKET_DELETE = '/.*\/baskets\/(?<basketId>.+)\/delete$/';

    public function process() {
        MerchantService::fromRegistry()->checkAuth(true);
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
                $basket = BasketServiceBuyNow::fromRegistry()->checkAdminPermission($pathParams['basketId']);
                BasketItemBuyNowRepository::fromRegistry()->deleteByBasketId($basket->getId());
                BasketBuyNowRepository::fromRegistry()->deleteById($basket->getId());
                RedirectServiceBuyNow::fromRegistry()->basketList(true);
            } elseif (preg_match(self::PATTERN_BASKET_EDIT, $request, $pathParams)) {
                $basket = BasketServiceBuyNow::fromRegistry()->checkAdminPermission($pathParams['basketId']);
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
            ->setPaidMaxCount(RequestParamsBuyNow::getBasketMaxPaidCount())
            ->setExpiresAt(DateTime::createFromFormat(ValidatorDateTimeLocal::DATE_TIME_LOCALE_FORMAT, RequestParamsBuyNow::getBasketExpiresAt()))
        ;
        $basketViewPage = $this->createBasketViewPage($basket);
        PageUtils::validateFormInputAndRenderOnError($basketViewPage);
        try {
            AdminControllerBuyNowShopConfigs::checkShopConfigPermission($basket->getShopConfigId());
            if ($basket->getId() != null) {
                BasketServiceBuyNow::fromRegistry()->checkAdminPermission($basket->getId());
            }
            $newBasketId = BasketBuyNowRepository::fromRegistry()->saveOrUpdate($basket);
            if ($basket->getId() != null)
                RedirectServiceBuyNow::fromRegistry()->basketList(true);
            else
                RedirectServiceBuyNow::fromRegistry()->basketEdit($newBasketId, true);
        } catch (Exception $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            $basketViewPage->render();
            exit(0);
        }
    }

    public function renderBasketListPage() {
        AdminBuyNowBasketListPage::builder()
            ->setBasketList(BasketBuyNowRepository::fromRegistry()->getByMerchantId(SessionServiceBridge::fromRegistry()->getMerchantUUID()))
            ->buildAndDisplay();
        exit(0);
    }

    public function createBasketViewPage($basket) {
        return AdminBuyNowBasketViewPage::builder()
            ->setBasket($basket)
            ->setBasketItems(BasketServiceBuyNow::fromRegistry()->getBasketItemList($basket));
    }

    public function renderBasketViewPage($basket) {
        $this
            ->createBasketViewPage($basket)
            ->buildAndDisplay();
        exit(0);
    }
}