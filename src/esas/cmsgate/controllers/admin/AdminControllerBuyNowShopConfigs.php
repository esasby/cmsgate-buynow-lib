<?php


namespace esas\cmsgate\controllers\admin;


use esas\cmsgate\bridge\ShopConfigBridge;
use esas\cmsgate\bridge\ShopConfigBuyNow;
use esas\cmsgate\BridgeConnector;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowProduct;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\htmlbuilder\FormUtils;
use esas\cmsgate\utils\htmlbuilder\page\PageUtils;
use esas\cmsgate\utils\RequestUtils;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\utils\StringUtils;
use esas\cmsgate\utils\URLUtils;
use esas\cmsgate\view\admin\AdminBuyNowShopConfigListPage;
use esas\cmsgate\view\admin\AdminBuyNowShopConfigViewPage;
use esas\cmsgate\view\RedirectServiceBuyNow;
use Exception;
use Throwable;

class AdminControllerBuyNowShopConfigs extends Controller
{
    const PATTERN_SHOP_CONFIGS_EDIT = '/.*\/shop_configs\/(?<shopConfigId>.+)$/';
    const PATTERN_SHOP_CONFIGS_DELETE = '/.*\/shop_configs\/(?<shopConfigId>.+)\/delete$/';

    public function process() {
        BridgeConnector::fromRegistry()->getMerchantService()->checkAuth(true);
        try {
            $request = RequestUtils::getRequestPath();
            if (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_ADMIN_SHOP_CONFIGS)) {
                if (RequestUtils::isMethodPost()) { // adding or updating
                    $this->addOrUpdateShopConfig();
                }
                $this->renderShopConfigListPage();
            } elseif (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_ADMIN_SHOP_CONFIGS_ADD)) {
                $this->renderShopConfigViewPage();
            } elseif (preg_match(self::PATTERN_SHOP_CONFIGS_DELETE, $request, $pathParams)) {
                $shopConfig = $this->checkShopConfigPermission($pathParams['shopConfigId']);
                BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository()->deleteById($shopConfig->getUuid());
                $this->renderShopConfigListPage();
            } elseif (preg_match(self::PATTERN_SHOP_CONFIGS_EDIT, $request, $pathParams)) {
                $this->checkShopConfigPermission($pathParams['shopConfigId']);
                $this->renderShopConfigViewPage();
            } else {
                $this->renderShopConfigListPage();
            }

        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        }
//        BridgeConnector::fromRegistry()->getAdminConfigPage()->render();
    }

    public function addOrUpdateShopConfig() {
        $shopConfigViewPage = new AdminBuyNowShopConfigViewPage();
        PageUtils::validateFormInputAndRenderOnError($shopConfigViewPage);
        try {
            if (RequestParamsBuyNow::getShopConfigId() != null) {
                $this->checkShopConfigPermission(RequestParamsBuyNow::getShopConfigId());
            }
//            else {
//                $newId = StringUtils::guidv4();
//                SessionUtilsBridge::setShopConfigUUID($newId);
//            }
            $shopConfig = new ShopConfigBuyNow();
            $shopConfig
                ->setUuid(RequestParamsBuyNow::getShopConfigId())
                ->setName(RequestParamsBuyNow::getShopConfigName())
                ->setActive(RequestParamsBuyNow::getShopConfigActive())
                ->setMerchantId(SessionUtilsBridge::getMerchantUUID())
                ->setConfigArray(FormUtils::extractInputsFromRequest($shopConfigViewPage->getForm(), [RequestParamsBuyNow::SHOP_CONFIG_NAME, RequestParamsBuyNow::SHOP_CONFIG_ACTIVE]))
            ;
            BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository()->saveOrUpdate($shopConfig);
            $this->renderShopConfigListPage();
        } catch (Exception $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            $shopConfigViewPage->render();
            exit(0);
        }
    }

    public function renderShopConfigListPage() {
        (new AdminBuyNowShopConfigListPage())->render();
        exit(0);
    }

    public function renderShopConfigViewPage() {
        (new AdminBuyNowShopConfigViewPage())->render();
        exit(0);
    }

    public static function checkShopConfigPermission($shopConfigId) {
        $shopConfig = BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository()->getByUUID($shopConfigId);
        if ($shopConfig == null || $shopConfig->getMerchantId() != SessionUtilsBridge::getMerchantUUID())
            throw new CMSGateException('This shop config can not be managed by current merchant');
        SessionUtilsBridge::setShopConfigUUID($shopConfig->getUuid());
        SessionUtilsBridge::setShopConfigObj($shopConfig);
        return $shopConfig;
    }
}