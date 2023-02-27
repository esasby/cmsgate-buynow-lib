<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\bridge\ShopConfigBuyNow;
use esas\cmsgate\BridgeConnectorBuyNow;
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

class AdminBuyNowShopConfigListPage extends AdminBuyNowPage
{
    /**
     * @var ShopConfigBuyNow[]
     */
    private $shopConfigList;

    public function __construct() {
        parent::__construct();
        $this->shopConfigList = BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository()->getByMerchantId(SessionUtilsBridge::getMerchantUUID());
    }


    public function elementPageContent() {
        return CardBuyNowHRO::builder()
            ->setCardHeaderI18n(AdminViewFieldsBuyNow::LABEL_SHOP_CONFIG_LIST)
            ->setCardBody(TableBuyNowHRO::builder()
                ->setTableHeaderColumns(['Id', 'Name', 'Active', 'Order counter'])
                ->setTableBody($this->elementShopConfigTableBody())
                ->build())
            ->setCardFooter(CardButtonsRowHRO::builder()
                ->addButtonI18n(AdminViewFields::ADD, RedirectServiceBuyNow::shopConfigAdd(), 'btn-secondary')
                ->build()
            )
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
}