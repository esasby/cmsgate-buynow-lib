<?php


namespace esas\cmsgate\buynow\controllers\admin;


use esas\cmsgate\bridge\BridgeConnector;
use esas\cmsgate\buynow\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\dao\ProductBuyNow;
use esas\cmsgate\buynow\hro\admin\AdminBuyNowProductListPage;
use esas\cmsgate\buynow\hro\admin\AdminBuyNowProductViewPage;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\htmlbuilder\page\PageUtils;
use esas\cmsgate\utils\RequestUtils;
use esas\cmsgate\bridge\service\SessionServiceBridge;
use esas\cmsgate\utils\StringUtils;
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
                $product = new ProductBuyNow();
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
            throw $e;
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            throw $e;
        }
//        BridgeConnector::fromRegistry()->getAdminConfigPage()->render();
    }

    public function addOrUpdateProduct() {
        $product = new ProductBuyNow();
        $product
            ->setId(RequestParamsBuyNow::getProductId()) // if presents
            ->setSku(RequestParamsBuyNow::getProductSKU())
            ->setName(RequestParamsBuyNow::getProductName())
            ->setDescription(RequestParamsBuyNow::getProductDescription())
            ->setActive(RequestParamsBuyNow::getProductActive())
            ->setPrice(RequestParamsBuyNow::getProductPrice())
            ->setCurrency(RequestParamsBuyNow::getProductCurrency())
            ->setImage(RequestParamsBuyNow::getProductImage())
            ->setMerchantId(SessionServiceBridge::fromRegistry()::getMerchantUUID());
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
            ->setProductList(BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->getByMerchantId(SessionServiceBridge::fromRegistry()::getMerchantUUID()))
            ->buildAndDisplay();
        exit(0);
    }

    /**
     * @param $product ProductBuyNow
     */
    public function renderProductViewPage($product) {
        $linkedBaskets = null;
        if (!empty($product->getId())) {
            $linkedBaskets = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getByProductId($product->getId());
        }
        AdminBuyNowProductViewPage::builder()
            ->setProduct($product)
            ->setLinkedBaskets($linkedBaskets)
            ->buildAndDisplay();
        exit(0);
    }

    public static function checkProductPermission($productId) {
        $product = BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->getById($productId);
        if ($product == null || $product->getMerchantId() != SessionServiceBridge::fromRegistry()::getMerchantUUID())
            throw new CMSGateException('This product can not be managed by current merchant');
        return $product;
    }
}