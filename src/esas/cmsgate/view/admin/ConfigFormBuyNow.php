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
        parent::__construct($managedFields, AdminViewFields::CONFIG_FORM_COMMON, RedirectServiceBuyNow::shopConfigList());
    }

}