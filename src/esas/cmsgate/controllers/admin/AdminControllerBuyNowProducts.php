<?php


namespace esas\cmsgate\controllers\admin;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowProduct;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\utils\htmlbuilder\page\PageUtils;
use esas\cmsgate\view\RedirectServiceBuyNow;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\RequestUtils;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\utils\StringUtils;
use esas\cmsgate\view\admin\AdminBuyNowProductListPage;
use esas\cmsgate\view\admin\AdminBuyNowProductViewPage;
use Exception;
use Throwable;

class AdminControllerBuyNowProducts extends Controller
{
    const PATTERN_PRODUCT_EDIT = '/.*\/products\/(?<productId>.+)$/';
    const PATTERN_PRODUCT_DELETE = '/.*\/products\/(?<productId>.+)\/delete$/';

    public function process() {
        BridgeConnector::fromRegistry()->getMerchantService()->checkAuth(true);
        try {
            $request = RequestUtils::getRequestPath();
            if (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_ADMIN_PRODUCTS)) {
                if (RequestUtils::isMethodPost()) { // adding or updating
                    $this->addOrUpdateProduct();
                }
                $this->renderProductListPage();
            } elseif (StringUtils::endsWith($request, RedirectServiceBuyNow::PATH_ADMIN_PRODUCTS_ADD)) {
                $product = new BuyNowProduct();
                $this->renderProductViewPage($product);
            } elseif (preg_match(self::PATTERN_PRODUCT_DELETE, $request, $pathParams)) {
                $product = $this->checkProductPermission($pathParams['productId']);
                BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->deleteByProductId($product->getId());
                BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->deleteById($product->getId());
                RedirectServiceBuyNow::productList(true);
            } elseif (preg_match(self::PATTERN_PRODUCT_EDIT, $request, $pathParams)) {
                $product = $this->checkProductPermission($pathParams['productId']);
                $this->renderProductViewPage($product);
            } else {
                $this->renderProductListPage();
            }

        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        }
//        BridgeConnector::fromRegistry()->getAdminConfigPage()->render();
    }

    public function addOrUpdateProduct() {
        $product = new BuyNowProduct();
        $product
            ->setId(RequestParamsBuyNow::getProductId()) // if presents
            ->setSku(RequestParamsBuyNow::getProductSKU())
            ->setName(RequestParamsBuyNow::getProductName())
            ->setDescription(RequestParamsBuyNow::getProductDescription())
            ->setActive(RequestParamsBuyNow::getProductActive())
            ->setPrice(RequestParamsBuyNow::getProductPrice())
            ->setCurrency(RequestParamsBuyNow::getProductCurrency())
            ->setMerchantId(SessionUtilsBridge::getMerchantUUID());
        $productEditPage = AdminBuyNowProductViewPage::builder()->setProduct($product);
        PageUtils::validateFormInputAndRenderOnError($productEditPage);
        try {
            if ($product->getId() != null) {
                $this->checkProductPermission($product->getId());
            }
            BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->saveOrUpdate($product);
            RedirectServiceBuyNow::productList(true);
        } catch (Exception $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            $productEditPage->render();
            exit(0);
        }
    }

    public function renderProductListPage() {
        AdminBuyNowProductListPage::builder()
            ->setProductList(BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->getByMerchantId(SessionUtilsBridge::getMerchantUUID()))
            ->buildAndDisplay();
        exit(0);
    }

    public function renderProductViewPage($product) {
        AdminBuyNowProductViewPage::builder()
            ->setProduct($product)->buildAndDisplay();
        exit(0);
    }

    public function checkProductPermission($productId) {
        $product = BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->getById($productId);
        if ($product == null || $product->getMerchantId() != SessionUtilsBridge::getMerchantUUID())
            throw new CMSGateException('This product can not be managed by current merchant');
        return $product;
    }
}