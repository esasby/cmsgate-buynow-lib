<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\buynow\BuyNowProduct;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactory;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;
use esas\cmsgate\view\RedirectServiceBuyNow;

class AdminBuyNowProductListPage extends AdminBuyNowPage
{
    private $productList;

    /**
     * @param array|BuyNowProduct[] $productList
     * @return AdminBuyNowProductListPage
     */
    public function setProductList($productList) {
        $this->productList = $productList;
        return $this;
    }

    public function elementPageContent() {
        return
            HROFactory::fromRegistry()->createDataListBuilder()
                ->setMainLabel(AdminViewFieldsBuyNow::PRODUCT_LIST)
                ->setTableHeaderColumns(['Id', 'SKU', 'Name', 'Description', 'Active', 'Price', 'Currency', 'Created At'])
                ->setTableBody($this->elementProductTableBody())
                ->addFooterButtonAdd(RedirectServiceBuyNow::productAdd())
                ->build();
    }

    public function elementProductTableBody() {
        $rows = '';
        foreach ($this->productList as $key => $value) {
            $rows .= $this->elementProductTableRow($value, $key);
        }
        return element::tbody($rows);
    }

    /**
     * @param BuyNowProduct $product
     */
    public function elementProductTableRow($product, $rowId) {
        return element::tr(
            attribute::clazz("position-relative"),
            element::td(TablePreset::elementTdStretchedLink($product->getId(), RedirectServiceBuyNow::productEdit($product->getId()))),
            element::td($product->getSku()),
            element::td($product->getName()),
            element::td($product->getDescription()),
            element::td(TablePreset::elementTdSwitch($product->isActive())),
            element::td($product->getPrice()),
            element::td($product->getCurrency()),
            element::td($product->getCreatedAt())
        );
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_PRODUCTS;
    }

    public static function builder() {
        return new AdminBuyNowProductListPage();
    }
}