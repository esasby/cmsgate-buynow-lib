<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowProduct;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\cards\CardButtonsRowHRO;
use esas\cmsgate\utils\htmlbuilder\presets\BootstrapPreset as bootstrap;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\view\hro\CardBuyNowHRO;
use esas\cmsgate\view\hro\TableBuyNowHRO;
use esas\cmsgate\view\RedirectServiceBuyNow;

class AdminBuyNowProductListPage extends AdminBuyNowPage
{
    private $productList;

    public function __construct() {
        parent::__construct();
        $this->productList = BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->getByMerchantId(SessionUtilsBridge::getMerchantUUID());;
    }


    public function elementPageContent() {
        return CardBuyNowHRO::builder()
            ->setCardHeaderI18n(AdminViewFieldsBuyNow::LABEL_PRODUCT_LIST)
            ->setCardBody(TableBuyNowHRO::builder()
                ->setTableHeaderColumns(['Id', 'SKU', 'Name', 'Description', 'Active', 'Price', 'Currency', 'Created At'])
                ->setTableBody($this->elementProductTableBody())
                ->build())
            ->setCardFooter(CardButtonsRowHRO::builder()
                ->addButtonI18n(AdminViewFields::ADD, RedirectServiceBuyNow::productAdd(), 'btn-secondary')
                ->build()
            )
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
}