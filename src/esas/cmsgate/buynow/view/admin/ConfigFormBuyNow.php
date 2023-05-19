<?php


namespace esas\cmsgate\buynow\view\admin;


use esas\cmsgate\bridge\view\admin\ConfigFormBridge;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\view\admin\ManagedFields;

class ConfigFormBuyNow extends ConfigFormBridge
{
    /**
     * ConfigFormBuyNow constructor.
     * @param $managedFields ManagedFields
     */
    public function __construct($managedFields) {
        parent::__construct($managedFields, AdminViewFields::CONFIG_FORM_COMMON, RedirectServiceBuyNow::fromRegistry()->shopConfigList());
    }

}