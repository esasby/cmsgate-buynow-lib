<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowProduct;
use esas\cmsgate\controllers\admin\AdminControllerBuyNowProducts;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\SessionUtils;
use esas\cmsgate\utils\SessionUtilsBridge;

class AdminBuyNowProductListPage extends AdminBuyNowPage
{
    public function elementPageContent() {
        return element::table(
            $this->elementProductTableHead(),
            $this->elementProductTableBody()
        );
    }

    public function elementProductTableHead() {
        return element::thead(
            element::tr(
                $this->elementProductTableHeadCol('#'),
                $this->elementProductTableHeadCol('SKU'),
                $this->elementProductTableHeadCol('Name'),
                $this->elementProductTableHeadCol('Description'),
                $this->elementProductTableHeadCol('Active'),
                $this->elementProductTableHeadCol('Price'),
                $this->elementProductTableHeadCol('Currency'),
                $this->elementProductTableHeadCol('Created At'),
            )
        );
    }

    public function elementProductTableHeadCol($label) {
        return element::th(
            attribute::scope("col"),
            element::content($label)
        );
    }

    public function elementProductTableBody() {
        $productList = BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->getByMerchantId(SessionUtilsBridge::getMerchantUUID());
        $rows = '';
        foreach ($productList as $key => $value) {
            $rows .= $this->elementProductTableRow($value, $key);
        }
        return element::tbody($rows);
    }

    /**
     * @param BuyNowProduct $product
     */
    public function elementProductTableRow($product, $rowId) {
        return element::tr(
            element::th(
                attribute::scope("row"),
                element::content($rowId)
            ),
            element::td($product->getSku()),
            element::td($product->getName()),
            element::td($product->getDescription()),
            element::td($product->isActive()),
            element::td($product->getPrice()),
            element::td($product->getCurrency()),
            element::td($product->getCreatedAt())
        );
    }

    public function getNavItemId() {
        return AdminControllerBuyNowProducts::PATH_ADMIN_PRODUCTS;
    }
}