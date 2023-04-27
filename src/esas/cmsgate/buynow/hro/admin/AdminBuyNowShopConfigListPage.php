<?php


namespace esas\cmsgate\buynow\hro\admin;


use esas\cmsgate\buynow\dao\ShopConfigBuyNow;
use esas\cmsgate\buynow\view\admin\AdminViewFieldsBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\hro\tables\DataListHROFactory;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;

class AdminBuyNowShopConfigListPage extends AdminBuyNowPage
{
    /**
     * @var ShopConfigBuyNow[]
     */
    private $shopConfigList;

    /**
     * @param ShopConfigBuyNow[] $shopConfigList
     * @return AdminBuyNowShopConfigListPage
     */
    public function setShopConfigList($shopConfigList) {
        $this->shopConfigList = $shopConfigList;
        return $this;
    }

    public function elementPageContent() {
        return
            DataListHROFactory::findBuilder()
                ->setMainLabel(AdminViewFieldsBuyNow::SHOP_CONFIG_LIST)
                ->setTableHeaderColumns(['Id', 'Name', 'Active', 'Order counter'])
                ->setTableBody($this->elementShopConfigTableBody())
                ->addFooterButtonAdd(RedirectServiceBuyNow::shopConfigAdd())
                ->build();
    }

    public function elementShopConfigTableBody() {
        $rows = '';
        foreach ($this->shopConfigList as $key => $value) {
            $rows .= $this->elementShopConfigTableRow($value, $key);
        }
        return element::tbody($rows);
    }

    /**
     * @param ShopConfigBuyNow $shopConfig
     */
    public function elementShopConfigTableRow($shopConfig, $rowId) {
        return element::tr(
            attribute::clazz("position-relative"),
            element::td(TablePreset::elementTdStretchedLink($shopConfig->getUuid(), RedirectServiceBuyNow::shopConfigEdit($shopConfig->getUuid()))),
            element::td($shopConfig->getName()),
            element::td(TablePreset::elementTdSwitch($shopConfig->isActive())),
            element::td($shopConfig->getOrderCounter())
        );
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_SHOP_CONFIGS;
    }

    public static function builder() {
        return new AdminBuyNowShopConfigListPage();
    }
}