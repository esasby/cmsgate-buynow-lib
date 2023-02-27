<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\bridge\ShopConfigBuyNow;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowBasket;
use esas\cmsgate\buynow\BuyNowBasketItem;
use esas\cmsgate\buynow\BuyNowProduct;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\page\SingleFormPage;
use esas\cmsgate\utils\htmlbuilder\presets\BootstrapPreset as bootstrap;
use esas\cmsgate\utils\htmlbuilder\presets\ScriptsPreset as script;
use esas\cmsgate\utils\htmlbuilder\presets\CssPreset as css;
use esas\cmsgate\utils\htmlbuilder\presets\CommonPreset as common;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;
use esas\cmsgate\utils\SessionUtils;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldList;
use esas\cmsgate\view\admin\fields\ConfigFieldNumber;
use esas\cmsgate\view\admin\fields\ConfigFieldText;
use esas\cmsgate\view\admin\fields\ConfigFieldTextarea;
use esas\cmsgate\view\admin\fields\ListOption;
use esas\cmsgate\view\admin\validators\ValidatorNotEmpty;
use esas\cmsgate\view\admin\validators\ValidatorNumeric;
use esas\cmsgate\view\RedirectServiceBuyNow;
use Exception;

class AdminBuyNowBasketItemViewPage extends AdminBuyNowPage implements SingleFormPage
{
    /**
     * @var BuyNowBasketItem
     */
    private $basketItem;
    /**
     * @var ConfigFormBridge
     */
    private $basketItemForm;

    /**
     * AdminBuyNowBasketItemViewPage constructor.
     * @param $basketItem BuyNowBasketItem
     */
    public function __construct($basketItem) {
        parent::__construct();
        $this->basketItem = $basketItem;
        $this->basketItemForm = $this->createBasketItemEditForm($basketItem);
    }

    public function elementPageContent() {
        return
            $this->elementMessages()
            . element::br()
            . $this->basketItemForm->generate();
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_BASKETS;
    }

    /**
     * @param $basketItem BuyNowBasketItem
     * @return ConfigFormBridge
     */
    private function createBasketItemEditForm($basketItem) {
        $managedFields = new ManagedFields();
        $fieldProduct
            = new ConfigFieldList(RequestParamsBuyNow::PRODUCT_ID, 'Product', '', true, $this->getProductsList());
        $fieldCount
            = new ConfigFieldNumber(RequestParamsBuyNow::BASKET_ITEM_PRODUCT_COUNT, 'Initial Count', '', true, new ValidatorNumeric());
        $fieldMaxCount
            = new ConfigFieldNumber(RequestParamsBuyNow::BASKET_ITEM_PRODUCT_MAX_COUNT, 'Max Count', '', true, new ValidatorNumeric());
        $managedFields->addField($fieldProduct->setValue($basketItem->getProductId()));
        $managedFields->addField($fieldCount->setValue($basketItem->getCount()));
        $managedFields->addField($fieldMaxCount->setValue($basketItem->getMaxCount()));
        $formKey = $basketItem->getId() != null ? AdminViewFieldsBuyNow::LABEL_BASKET_ITEM_EDIT : AdminViewFieldsBuyNow::LABEL_BASKET_ITEM_ADD;
        $basketForm = new ConfigFormBridge($managedFields, $formKey, RedirectServiceBuyNow::basketItemAdd($basketItem->getBasketId()));
        if ($basketItem->getId() != null) {
            $basketForm->addFooterButtonDelete(RedirectServiceBuyNow::basketItemDelete($basketItem->getBasketId(), $basketItem->getId()));
            $basketForm->addHiddenInput(RequestParamsBuyNow::BASKET_ITEM_ID, $basketItem->getId());
        }
        $basketForm->addFooterButtonCancel(RedirectServiceBuyNow::basketEdit($basketItem->getBasketId()));
        return $basketForm;
    }

    /**
     * @return ListOption[]
     */
    public function getProductsList() {
        $options = array();
        /** @var BuyNowProduct[] $products */
        $products = BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->getByMerchantId(SessionUtilsBridge::getMerchantUUID());
        foreach ($products as $product) {
            $options[] = new ListOption($product->getId(), $product->getName());
        }
        return $options;
    }

    public function getForm() {
        return $this->basketItemForm;
    }
}