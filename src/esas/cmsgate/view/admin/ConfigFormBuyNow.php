<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\lang\Translator;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldText;
use esas\cmsgate\view\admin\validators\ValidatorNotEmpty;
use esas\cmsgate\view\RedirectServiceBuyNow;

class ConfigFormBuyNow extends ConfigFormBridge
{
    /**
     * ConfigFormBuyNow constructor.
     * @param $managedFields ManagedFields
     */
    public function __construct($managedFields) {
        //adding cms-hosted fields
        $managedFields->addField(new ConfigFieldText(
            RequestParamsBuyNow::SHOP_CONFIG_NAME, Translator::fromRegistry()->translate(RequestParamsBuyNow::SHOP_CONFIG_NAME), '', true, new ValidatorNotEmpty(), false));
        $managedFields->addField(new ConfigFieldCheckbox(
            RequestParamsBuyNow::SHOP_CONFIG_ACTIVE, 'Active', '', true, null, false));
        parent::__construct($managedFields, AdminViewFields::CONFIG_FORM_COMMON, RedirectServiceBuyNow::shopConfigList());
    }

    public function generate() {
        $formKey = SessionUtilsBridge::getShopConfigUUID() != null ? AdminViewFieldsBuyNow::SHOP_CONFIG_EDIT_FORM : AdminViewFieldsBuyNow::SHOP_CONFIG_ADD_FORM;
        $this->setHeadingTitle(Translator::fromRegistry()->translate($formKey));
        if (SessionUtilsBridge::getShopConfigUUID() != null) {
            $this->addFooterButtonDelete(RedirectServiceBuyNow::shopConfigDelete(SessionUtilsBridge::getShopConfigUUID()));
            $this->addHiddenInput(RequestParamsBuyNow::SHOP_CONFIG_ID, SessionUtilsBridge::getShopConfigUUID());
        }
        $this->addFooterButtonCancel(RedirectServiceBuyNow::shopConfigList());
        return parent::generate();
    }
}