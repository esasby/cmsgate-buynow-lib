<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowBasket;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactory;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;
use esas\cmsgate\view\RedirectServiceBuyNow;

class AdminBuyNowBasketListPage extends AdminBuyNowPage
{
    private $basketList;

    /**
     * @param array|BuyNowBasket[] $basketList
     * @return AdminBuyNowBasketListPage
     */
    public function setBasketList($basketList) {
        $this->basketList = $basketList;
        return $this;
    }

    public function elementPageContent() {
        return
            HROFactory::fromRegistry()->createDataListBuilder()
                ->setMainLabel(AdminViewFieldsBuyNow::BASKET_LIST)
                ->setTableHeaderColumns(['Id', 'Shop config', 'Name', 'Description', 'Active', 'Ask name', 'Ask phone', 'Ask email', 'Checkout counter', 'Created At'])
                ->setTableBody($this->elementBasketTableBody())
                ->addFooterButtonAdd(RedirectServiceBuyNow::basketAdd())
                ->build();
    }

    public function elementBasketTableBody() {
        $rows = '';
        foreach ($this->basketList as $key => $value) {
            $rows .= $this->elementBasketTableRow($value, $key);
        }
        return element::tbody($rows);
    }

    /**
     * @param BuyNowBasket $basket
     */
    public function elementBasketTableRow($basket, $rowId) {
        $shopConfig = BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository()->getByUUID($basket->getShopConfigId());
        return element::tr(
            attribute::clazz("position-relative"),
            element::td(TablePreset::elementTdStretchedLink($basket->getId(), RedirectServiceBuyNow::basketEdit($basket->getId()))),
            element::td($shopConfig->getName()), //todo name
            element::td($basket->getName()),
            element::td($basket->getDescription()),
            element::td(TablePreset::elementTdSwitch($basket->isActive())),
            element::td(TablePreset::elementTdSwitch($basket->isAskFIO())),
            element::td(TablePreset::elementTdSwitch($basket->isAskPhone())),
            element::td(TablePreset::elementTdSwitch($basket->isAskEmail())),
            element::td($basket->getCheckoutCount()),
            element::td($basket->getCreatedAt())
        );
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_BASKETS;
    }

    public static function builder() {
        return new AdminBuyNowBasketListPage();
    }
}