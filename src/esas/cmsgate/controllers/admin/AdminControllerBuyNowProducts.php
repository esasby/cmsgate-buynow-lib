<?php


namespace esas\cmsgate\controllers\admin;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowProduct;
use esas\cmsgate\controllers\Controller;
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
    const PATH_ADMIN_PRODUCTS = '/admin/products';
    const PATH_ADMIN_PRODUCTS_ADD = '/admin/products/add';
    const PATTERN_PRODUCT_SELECTED = '.*/products/(?<productId>\w+)$';

    public function process() {
        BridgeConnector::fromRegistry()->getMerchantService()->checkAuth(true);
        try {
            $request = RequestUtils::getRequestPath();
            if (StringUtils::endsWith($request, self::PATH_ADMIN_PRODUCTS)) {
                if (RequestUtils::isMethodPost()) { // adding or updating
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
                    if ($product->getId() != null) {
                        $this->checkProductPermission($product->getId());
                    }
                    BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->saveOrUpdate($product);
                }
                // go to product list
            } elseif (StringUtils::endsWith($request, self::PATH_ADMIN_PRODUCTS_ADD)) {
                $product = new BuyNowProduct();
                (new AdminBuyNowProductViewPage($product))->render();
            } elseif (preg_match(self::PATTERN_PRODUCT_SELECTED, $request, $pathParams)) {
                $this->checkProductPermission($pathParams['productId']);
                if (RequestUtils::isMethodDelete()) {
                    BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->deleteByProductId($product->getId());
                    BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->deleteById($product->getId());
                    // go to product list
                } else
                    (new AdminBuyNowProductViewPage($product))->render();
            } else {
                (new AdminBuyNowProductListPage())->render();
            }

        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        }
        BridgeConnector::fromRegistry()->getAdminConfigPage()->render();
    }

    public function checkProductPermission($productId) {
        $product = BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->getById($productId);
        if ($product == null || $product->getMerchantId() != SessionUtilsBridge::getMerchantUUID())
            throw new CMSGateException('This product can not be managed by current merchant');
    }

}