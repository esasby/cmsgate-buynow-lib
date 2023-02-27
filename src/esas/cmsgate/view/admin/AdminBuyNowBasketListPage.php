<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowBasket;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\cards\CardButtonsRowHRO;
use esas\cmsgate\utils\htmlbuilder\hro\tables\TableHRO;
use esas\cmsgate\utils\htmlbuilder\presets\BootstrapPreset as bootstrap;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\view\hro\CardBuyNowHRO;
use esas\cmsgate\view\hro\TableBuyNowHRO;
use esas\cmsgate\view\RedirectServiceBuyNow;

class AdminBuyNowBasketListPage extends AdminBuyNowPage
{
    private $basketList;

    public function __construct() {
        parent::__construct();
        $this->basketList = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getByMerchantId(SessionUtilsBridge::getMerchantUUID());;
    }

    public function elementPageContent() {
        return CardBuyNowHRO::builder()
            ->setCardHeaderI18n(AdminViewFieldsBuyNow::LABEL_BASKET_LIST)
            ->setCardBody(TableBuyNowHRO::builder()
                ->setTableHeaderColumns(['Id', 'Shop config', 'Name', 'Description', 'Active', 'Ask name', 'Ask phone', 'Ask email', 'Checkout counter', 'Created At'])
                ->setTableBody($this->elementBasketTableBody())
                ->build())
            ->setCardFooter(CardButtonsRowHRO::builder()
                ->addButtonI18n(AdminViewFields::ADD, RedirectServiceBuyNow::basketAdd(), 'btn-secondary')
                ->build()
            )
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
}