<?php


namespace esas\cmsgate\buynow\hro\admin;


use esas\cmsgate\buynow\dao\ProductBuyNow;
use esas\cmsgate\buynow\view\admin\AdminViewFieldsBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\hro\tables\DataListHROFactory;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;

class AdminBuyNowProductListPage extends AdminBuyNowPage
{
    private $productList;

    /**
     * @param array|ProductBuyNow[] $productList
     * @return AdminBuyNowProductListPage
     */
    public function setProductList($productList) {
        $this->productList = $productList;
        return $this;
    }

    public function elementPageContent() {
        return
            DataListHROFactory::findBuilder()
                ->setMainLabel(AdminViewFieldsBuyNow::PRODUCT_LIST)
                ->setTableHeaderColumns(['Id', 'SKU', 'Name', 'Description', 'Image', 'Active', 'Price', 'Currency', 'Created At'])
                ->setTableBody($this->elementProductTableBody())
                ->addFooterButtonAdd(RedirectServiceBuyNow::fromRegistry()->productAdd())
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
     * @param ProductBuyNow $product
     */
    public function elementProductTableRow($product, $rowId) {
        return element::tr(
            attribute::clazz("position-relative"),
            element::td(TablePreset::elementTdStretchedLink($product->getId(), RedirectServiceBuyNow::fromRegistry()->productEdit($product->getId()))),
            element::td($product->getSku()),
            element::td($product->getName()),
            element::td($product->getDescription()),
            element::td(element::img(
                attribute::src($product->getImage()),
                attribute::clazz('img-thumbnail'),
                attribute::width('200')
            )),
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