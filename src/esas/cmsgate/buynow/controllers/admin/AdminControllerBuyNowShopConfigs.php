<?php


namespace esas\cmsgate\buynow\controllers\admin;


use esas\cmsgate\bridge\dao\ShopConfigRepository;
use esas\cmsgate\bridge\service\MerchantService;
use esas\cmsgate\bridge\service\SessionServiceBridge;
use esas\cmsgate\buynow\dao\BasketBuyNowRepository;
use esas\cmsgate\buynow\dao\ShopConfigBuyNow;
use esas\cmsgate\buynow\hro\admin\AdminBuyNowShopConfigListPage;
use esas\cmsgate\buynow\hro\admin\AdminBuyNowShopConfigViewPage;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\htmlbuilder\FormUtils;
use esas\cmsgate\utils\htmlbuilder\page\PageUtils;
use esas\cmsgate\utils\RequestUtils;
use esas\cmsgate\utils\StringUtils;
use Exception;
use Throwable;

class AdminControllerBuyNowShopConfigs extends Controller
{
    const PATTERN_SHOP_CONFIGS_EDIT = '/.*\/shop_configs\/(?<shopConfigId>.+)$/';
    const PATTERN_SHOP_CONFIGS_DELETE = '/.*\/shop_configs\/(?<shopConfigId>.+)\/delete$/';

    public function process() {
        MerchantService::fromRegistry()->checkAuth(true);
        try {
            $request = RequestUtils::getRequestPath();
            if (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_ADMIN_SHOP_CONFIGS)) {
                if (RequestUtils::isMethodPost()) { // adding or updating
                    $this->addOrUpdateShopConfig();
                }
                $this->renderShopConfigListPage();
            } elseif (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_ADMIN_SHOP_CONFIGS_ADD)) {
                $shopConfig = new ShopConfigBuyNow();
                $this->renderShopConfigViewPage($shopConfig);
            } elseif (preg_match(self::PATTERN_SHOP_CONFIGS_DELETE, $request, $pathParams)) {
                $shopConfig = $this->checkShopConfigPermission($pathParams['shopConfigId']);
                ShopConfigRepository::fromRegistry()->deleteById($shopConfig->getUuid());
                $this->renderShopConfigListPage();
            } elseif (preg_match(self::PATTERN_SHOP_CONFIGS_EDIT, $request, $pathParams)) {
                $shopConfig = $this->checkShopConfigPermission($pathParams['shopConfigId']);
                $this->renderShopConfigViewPage($shopConfig);
            } else {
                $this->renderShopConfigListPage();
            }

        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        }
    }

    public function addOrUpdateShopConfig() {
        $shopConfigViewPage = new AdminBuyNowShopConfigViewPage();
        PageUtils::validateFormInputAndRenderOnError($shopConfigViewPage);
        try {
            if (RequestParamsBuyNow::getShopConfigId() != null) {
                $this->checkShopConfigPermission(RequestParamsBuyNow::getShopConfigId());
            }
            $shopConfig = new ShopConfigBuyNow();
            $shopConfig
                ->setUuid(RequestParamsBuyNow::getShopConfigId())
                ->setName(RequestParamsBuyNow::getShopConfigName())
                ->setActive(RequestParamsBuyNow::getShopConfigActive())
                ->setMerchantId(SessionServiceBridge::fromRegistry()->getMerchantUUID())
                ->setConfigArray(FormUtils::extractInputsFromRequest($shopConfigViewPage->getFormFields(), [RequestParamsBuyNow::SHOP_CONFIG_NAME, RequestParamsBuyNow::SHOP_CONFIG_ACTIVE]));
            ShopConfigRepository::fromRegistry()->saveOrUpdate($shopConfig);
            RedirectServiceBuyNow::fromRegistry()->shopConfigList(true);
        } catch (Exception $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            $shopConfigViewPage->render();
            exit(0);
        }
    }

    public function renderShopConfigListPage() {
        AdminBuyNowShopConfigListPage::builder()
            ->setShopConfigList(ShopConfigRepository::fromRegistry()->getByMerchantId(SessionServiceBridge::fromRegistry()->getMerchantUUID()))
            ->buildAndDisplay();
        exit(0);
    }

    /**
     * @param $shopConfig ShopConfigBuyNow
     */
    public function renderShopConfigViewPage($shopConfig) {
        $linkedBaskets = null;
        if (!empty($shopConfig->getId()))
            $linkedBaskets = BasketBuyNowRepository::fromRegistry()->getByShopConfigId($shopConfig->getId()) ;
        AdminBuyNowShopConfigViewPage::builder()
            ->setShopConfig($shopConfig)
            ->setLinkedBaskets($linkedBaskets)
            ->buildAndDisplay();
        exit(0);
    }

    /**
     * @param $shopConfigId
     * @return ShopConfigBuyNow
     * @throws CMSGateException
     */
    public static function checkShopConfigPermission($shopConfigId) {
        /** @var ShopConfigBuyNow $shopConfig */
        $shopConfig = ShopConfigRepository::fromRegistry()->getById($shopConfigId);
        if ($shopConfig == null || $shopConfig->getMerchantId() != SessionServiceBridge::fromRegistry()->getMerchantUUID())
            throw new CMSGateException('This shop config can not be managed by current merchant');
        SessionServiceBridge::fromRegistry()->setShopConfigUUID($shopConfig->getId());
        SessionServiceBridge::fromRegistry()->setShopConfigObj($shopConfig);
        return $shopConfig;
    }
}