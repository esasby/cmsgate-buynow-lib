<?php


namespace esas\cmsgate\buynow\hro\admin;


use esas\cmsgate\bridge\service\SessionServiceBridge;
use esas\cmsgate\buynow\dao\BasketBuyNow;
use esas\cmsgate\buynow\dao\ShopConfigBuyNow;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\buynow\view\admin\AdminViewFieldsBuyNow;
use esas\cmsgate\hro\forms\FormHROFactory;
use esas\cmsgate\hro\tables\DataListHROFactory;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\page\AddOrUpdatePage;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldText;
use esas\cmsgate\view\admin\ManagedFields;
use esas\cmsgate\view\admin\validators\ValidatorNotEmpty;

class AdminBuyNowShopConfigViewPage extends AdminBuyNowPage implements AddOrUpdatePage
{

    /**
     * @var ShopConfigBuyNow
     */
    private $shopConfig;

    /**
     * @var BasketBuyNow[]
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
     * @param BasketBuyNow[] $linkedBaskets
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
        $form = FormHROFactory::findBuilder()
            ->setId(SessionServiceBridge::fromRegistry()->getShopConfigUUID() != null ? AdminViewFieldsBuyNow::SHOP_CONFIG_EDIT_FORM : AdminViewFieldsBuyNow::SHOP_CONFIG_ADD_FORM)
            ->setAction(RedirectServiceBuyNow::fromRegistry()->shopConfigList())
            ->setManagedFields($this->managedFields)
            ->addButtonSave()
            ->addButtonCancel(RedirectServiceBuyNow::fromRegistry()->shopConfigList());
        if ($this->isEditMode()) {
            $form->addButtonDelete(RedirectServiceBuyNow::fromRegistry()->shopConfigDelete(SessionServiceBridge::fromRegistry()->getShopConfigUUID()));
            $form->addHiddenInput(RequestParamsBuyNow::SHOP_CONFIG_ID, SessionServiceBridge::fromRegistry()->getShopConfigUUID());
        }
        return $form->build();
    }

    protected function elementLinkedBasketsPanel() {
        if ($this->linkedBaskets == null)
            return '';
        return element::br() . element::br() .
            DataListHROFactory::findBuilder()
                ->setMainLabel(AdminViewFieldsBuyNow::SHOP_CONFIG_LINKED_BASKET_LIST)
                ->setTableHeaderColumns(['Id', 'Name', 'Active', 'Ask name', 'Ask phone', 'Ask email', 'Checkout counter', 'Created At'])
                ->setTableBody($this->elementLinkedBasketsTableBody())
                ->addFooterButtonAdd(RedirectServiceBuyNow::fromRegistry()->basketAdd())
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
     * @param BasketBuyNow $basket
     */
    public function elementLinkedBasketsTableRow($basket, $rowId) {
        return element::tr(
            attribute::clazz("position-relative"),
            element::td(TablePreset::elementTdStretchedLink($basket->getId(), RedirectServiceBuyNow::fromRegistry()->basketEdit($basket->getId()))),
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