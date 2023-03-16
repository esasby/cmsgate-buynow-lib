<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\bridge\ShopConfigBuyNow;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactory;
use esas\cmsgate\utils\htmlbuilder\page\AddOrUpdatePage;
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
     * @var ConfigFormBridge
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

    public function elementPageContent() {
        return $this->elementMessages()
            . element::br()
            . $this->elementShopConfigEditForm();
    }

    public function elementShopConfigEditForm() {
        $form = HROFactory::fromRegistry()->createFormBuilder()
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

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_SHOP_CONFIGS;
    }

    public function getFormFields() {
        return $this->managedFields;
    }

    public function isEditMode() {
        return $this->shopConfig != null && $this->shopConfig->getUuid() != null;
    }

    public static function builder() {
        return new AdminBuyNowShopConfigViewPage();
    }
}