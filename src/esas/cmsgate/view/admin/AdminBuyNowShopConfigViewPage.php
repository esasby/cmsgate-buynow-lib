<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\bridge\ShopConfigBuyNow;
use esas\cmsgate\buynow\BuyNowBasket;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactoryCmsGate;
use esas\cmsgate\utils\htmlbuilder\page\AddOrUpdatePage;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldText;
use esas\cmsgate\view\admin\validators\ValidatorNotEmpty;
use esas\cmsgate\view\RedirectServiceBuyNow;

class AdminBuyNowShopConfigViewPage extends AdminBuyNowPage implements AddOrUpdatePage
{

    /**
     * @var ShopConfigBuyNow
     */
    private $shopConfig;

    /**
     * @var BuyNowBasket[]
     */
    private $linkedBaskets;

    /**
     * @var ManagedFields
     */
    private $managedFields;

    /**
     * AdminBuyNowShopConfigViewPage constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->managedFields = Registry::getRegistry()->getConfigForm()->getManagedFields()
            ->addField(new ConfigFieldText(RequestParamsBuyNow::SHOP_CONFIG_NAME, Translator::fromRegistry()->translate(RequestParamsBuyNow::SHOP_CONFIG_NAME), '', true, new ValidatorNotEmpty(), false))
            ->addField(new ConfigFieldCheckbox(RequestParamsBuyNow::SHOP_CONFIG_ACTIVE, 'Active', '', true, null, false));
    }

    /**
     * @param ShopConfigBuyNow $shopConfig
     * @return AdminBuyNowShopConfigViewPage
     */
    public function setShopConfig($shopConfig) {
        $this->shopConfig = $shopConfig;
        if ($this->shopConfig != null) {
            $this->managedFields->getField(RequestParamsBuyNow::SHOP_CONFIG_NAME)->setValue($shopConfig->getName());
            $this->managedFields->getField(RequestParamsBuyNow::SHOP_CONFIG_ACTIVE)->setValue($shopConfig->isActive());
        }
        return $this;
    }

    /**
     * @param BuyNowBasket[] $linkedBaskets
     * @return AdminBuyNowShopConfigViewPage
     */
    public function setLinkedBaskets($linkedBaskets) {
        $this->linkedBaskets = $linkedBaskets;
        return $this;
    }

    public function elementPageContent() {
        return $this->elementShopConfigEditForm()
            . $this->elementLinkedBasketsPanel();
    }

    public function elementShopConfigEditForm() {
        $form = HROFactoryCmsGate::fromRegistry()->createFormBuilder()
            ->setId(SessionUtilsBridge::getShopConfigUUID() != null ? AdminViewFieldsBuyNow::SHOP_CONFIG_EDIT_FORM : AdminViewFieldsBuyNow::SHOP_CONFIG_ADD_FORM)
            ->setAction(RedirectServiceBuyNow::shopConfigList())
            ->setManagedFields($this->managedFields)
            ->addButtonSave()
            ->addButtonCancel(RedirectServiceBuyNow::shopConfigList());
        if ($this->isEditMode()) {
            $form->addButtonDelete(RedirectServiceBuyNow::shopConfigDelete(SessionUtilsBridge::getShopConfigUUID()));
            $form->addHiddenInput(RequestParamsBuyNow::SHOP_CONFIG_ID, SessionUtilsBridge::getShopConfigUUID());
        }
        return $form->build();
    }

    protected function elementLinkedBasketsPanel() {
        if ($this->linkedBaskets == null)
            return '';
        return element::br() . element::br() .
            HROFactoryCmsGate::fromRegistry()->createDataListBuilder()
                ->setMainLabel(AdminViewFieldsBuyNow::SHOP_CONFIG_LINKED_BASKET_LIST)
                ->setTableHeaderColumns(['Id', 'Name', 'Active', 'Ask name', 'Ask phone', 'Ask email', 'Checkout counter', 'Created At'])
                ->setTableBody($this->elementLinkedBasketsTableBody())
                ->addFooterButtonAdd(RedirectServiceBuyNow::basketAdd())
                ->build();
    }

    public function elementLinkedBasketsTableBody() {
        $rows = '';
        foreach ($this->linkedBaskets as $key => $value) {
            $rows .= $this->elementLinkedBasketsTableRow($value, $key);
        }
        return element::tbody($rows);
    }

    /**
     * @param BuyNowBasket $basket
     */
    public function elementLinkedBasketsTableRow($basket, $rowId) {
        return element::tr(
            attribute::clazz("position-relative"),
            element::td(TablePreset::elementTdStretchedLink($basket->getId(), RedirectServiceBuyNow::basketEdit($basket->getId()))),
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
        return RedirectServiceBuyNow::PATH_ADMIN_SHOP_CONFIGS;
    }

    public function getFormFields() {
        return $this->managedFields;
    }

    public function isEditMode() {
        return $this->shopConfig != null && $this->shopConfig->getId() != null;
    }

    public static function builder() {
        return new AdminBuyNowShopConfigViewPage();
    }
}