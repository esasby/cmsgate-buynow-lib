<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\buynow\BuyNowProduct;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\page\SingleFormPage;
use esas\cmsgate\utils\htmlbuilder\presets\ScriptsPreset as script;
use esas\cmsgate\utils\htmlbuilder\presets\CssPreset as css;
use esas\cmsgate\utils\htmlbuilder\presets\CommonPreset as common;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldNumber;
use esas\cmsgate\view\admin\fields\ConfigFieldText;
use esas\cmsgate\view\admin\fields\ConfigFieldTextarea;
use esas\cmsgate\view\admin\validators\ValidatorNotEmpty;
use esas\cmsgate\view\admin\validators\ValidatorNumeric;
use esas\cmsgate\view\RedirectServiceBuyNow;
use Exception;

class AdminBuyNowProductViewPage extends AdminBuyNowPage implements SingleFormPage
{
    /**
     * @var BuyNowProduct
     */
    private $product;
    /**
     * @var ConfigFormBridge
     */
    private $productEditForm;

    public function __construct($product) {
        parent::__construct();
        $this->product = $product;
        $this->productEditForm = $this->createProductEditForm($product);
    }

    public function elementPageContent() {
        return $this->elementMessages()
            . element::br()
            . $this->productEditForm->generate();
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_PRODUCTS;
    }

    /**
     * @param $product BuyNowProduct
     * @return ConfigFormBridge
     */
    private function createProductEditForm($product) {
        $productFields = new ManagedFields();
        $fieldSku
            = new ConfigFieldText(RequestParamsBuyNow::PRODUCT_SKU, 'SKU', '', true, new ValidatorNotEmpty(), false);
        $fieldName
            = new ConfigFieldText(RequestParamsBuyNow::PRODUCT_NAME, 'Name', '', true, new ValidatorNotEmpty(), false);
        $fieldDescription
            = new ConfigFieldTextarea(RequestParamsBuyNow::PRODUCT_DESCRIPTION, 'Description', '', true, null, 20, 4);
        $fieldActive
            = new ConfigFieldCheckbox(RequestParamsBuyNow::PRODUCT_ACTIVE, 'Active?', '', true, null);
        $fieldPrice
            = new ConfigFieldNumber(RequestParamsBuyNow::PRODUCT_PRICE, 'Price', '', true, new ValidatorNumeric());
        $fieldCurrency
            = new ConfigFieldText(RequestParamsBuyNow::PRODUCT_CURRENCY, 'Currency', '', true, new ValidatorNotEmpty(), true);
        $productFields->addField($fieldSku->setValue($product->getSku()));
        $productFields->addField($fieldName->setValue($product->getName()));
        $productFields->addField($fieldDescription->setValue($product->getDescription()));
        $productFields->addField($fieldActive->setValue($product->isActive()));
        $productFields->addField($fieldPrice->setValue($product->getPrice()));
        $productFields->addField($fieldCurrency->setValue('BYN'));
        $formKey = $product->getId() != null ? AdminViewFieldsBuyNow::LABEL_PRODUCT_EDIT : AdminViewFieldsBuyNow::LABEL_PRODUCT_ADD;
        $productForm = new ConfigFormBridge($productFields, $formKey, RedirectServiceBuyNow::productList());
        if ($product->getId() != null) {
            $productForm->addFooterButtonDelete(RedirectServiceBuyNow::productDelete($product->getId()));
            $productForm->addHiddenInput(RequestParamsBuyNow::PRODUCT_ID, $product->getId());
        }
        $productForm->addFooterButtonCancel(RedirectServiceBuyNow::productList());
        return $productForm;
    }

    public function getForm() {
        return $this->productEditForm;
    }
}