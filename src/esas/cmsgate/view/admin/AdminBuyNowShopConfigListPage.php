<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\bridge\ShopConfigBuyNow;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactory;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;
use esas\cmsgate\view\RedirectServiceBuyNow;

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
            HROFactory::fromRegistry()->createDataListBuilder()
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