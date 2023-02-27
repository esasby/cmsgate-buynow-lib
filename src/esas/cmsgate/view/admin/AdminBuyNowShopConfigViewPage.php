<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\page\SingleFormPage;
use esas\cmsgate\view\RedirectServiceBuyNow;
use Exception;

class AdminBuyNowShopConfigViewPage extends AdminBuyNowPage implements SingleFormPage
{
    /**
     * @var ConfigFormBridge
     */
    private $shopConfigForm;

    public function __construct($shopConfigForm = null) {
        parent::__construct();
        if ($shopConfigForm != null)
            $this->shopConfigForm = $shopConfigForm;
        else
            $this->shopConfigForm = Registry::getRegistry()->getConfigForm();
    }

    public function elementPageContent() {
        return $this->elementMessages()
            . element::br()
            . $this->shopConfigForm->generate();
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_SHOP_CONFIGS;
    }

    public function getForm() {
        return $this->shopConfigForm;
    }
}