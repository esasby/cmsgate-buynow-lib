<?php


namespace esas\cmsgate\buynow\hro\admin;


use esas\cmsgate\bridge\dao\ShopConfigRepository;
use esas\cmsgate\buynow\dao\BasketBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\buynow\view\admin\AdminViewFieldsBuyNow;
use esas\cmsgate\hro\tables\DataListHROFactory;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;

class AdminBuyNowBasketListPage extends AdminBuyNowPage
{
    private $basketList;

    /**
     * @param array|BasketBuyNow[] $basketList
     * @return AdminBuyNowBasketListPage
     */
    public function setBasketList($basketList) {
        $this->basketList = $basketList;
        return $this;
    }

    public function elementPageContent() {
        return
            DataListHROFactory::findBuilder()
                ->setMainLabel(AdminViewFieldsBuyNow::BASKET_LIST)
                ->setTableHeaderColumns(['Id', 'Shop config', 'Name', 'Active', 'Ask name', 'Ask phone', 'Ask email', 'Checkout counter', 'Created At'])
                ->setTableBody($this->elementBasketTableBody())
                ->addFooterButtonAdd(RedirectServiceBuyNow::fromRegistry()->basketAdd())
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
     * @param BasketBuyNow $basket
     */
    public function elementBasketTableRow($basket, $rowId) {
        $shopConfig = ShopConfigRepository::fromRegistry()->getById($basket->getShopConfigId());
        return element::tr(
            attribute::clazz("position-relative"),
            element::td(TablePreset::elementTdStretchedLink($basket->getId(), RedirectServiceBuyNow::fromRegistry()->basketEdit($basket->getId()))),
            element::td($shopConfig->getName()), //todo name
            element::td($basket->getName()),
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