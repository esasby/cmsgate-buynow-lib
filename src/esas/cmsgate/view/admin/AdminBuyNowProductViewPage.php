<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\buynow\BuyNowProduct;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactory;
use esas\cmsgate\utils\htmlbuilder\page\AddOrUpdatePage;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldNumber;
use esas\cmsgate\view\admin\fields\ConfigFieldText;
use esas\cmsgate\view\admin\fields\ConfigFieldTextarea;
use esas\cmsgate\view\admin\validators\ValidatorNotEmpty;
use esas\cmsgate\view\admin\validators\ValidatorNumeric;
use esas\cmsgate\view\RedirectServiceBuyNow;

class AdminBuyNowProductViewPage extends AdminBuyNowPage implements AddOrUpdatePage
{
    /**
     * @var ManagedFields
     */
    private $productFields;

    /**
     * @var BuyNowProduct
     */
    private $product;

    /**
     * AdminBuyNowProductViewPage constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->productFields = new ManagedFields();
        $this->productFields
            ->addField(new ConfigFieldText(RequestParamsBuyNow::PRODUCT_SKU, 'SKU', '', true, new ValidatorNotEmpty(), false))
            ->addField(new ConfigFieldText(RequestParamsBuyNow::PRODUCT_NAME, 'Name', '', true, new ValidatorNotEmpty(), false))
            ->addField(new ConfigFieldTextarea(RequestParamsBuyNow::PRODUCT_DESCRIPTION, 'Description', '', true, null, 20, 4))
            ->addField(new ConfigFieldCheckbox(RequestParamsBuyNow::PRODUCT_ACTIVE, 'Active?', '', true, null))
            ->addField(new ConfigFieldNumber(RequestParamsBuyNow::PRODUCT_PRICE, 'Price', '', true, new ValidatorNumeric()))
            ->addField(new ConfigFieldText(RequestParamsBuyNow::PRODUCT_CURRENCY, 'Currency', '', true, new ValidatorNotEmpty(), true));
    }

    /**
     * @param BuyNowProduct $product
     * @return AdminBuyNowProductViewPage
     */
    public function setProduct($product) {
        $this->product = $product;
        if ($this->product != null) {
            $this->productFields->getField(RequestParamsBuyNow::PRODUCT_SKU)->setValue($this->product->getSku());
            $this->productFields->getField(RequestParamsBuyNow::PRODUCT_NAME)->setValue($this->product->getName());
            $this->productFields->getField(RequestParamsBuyNow::PRODUCT_DESCRIPTION)->setValue($this->product->getDescription());
            $this->productFields->getField(RequestParamsBuyNow::PRODUCT_ACTIVE)->setValue($this->product->isActive());
            $this->productFields->getField(RequestParamsBuyNow::PRODUCT_PRICE)->setValue($this->product->getPrice());
            $this->productFields->getField(RequestParamsBuyNow::PRODUCT_CURRENCY)->setValue('BYN');
        }
        return $this;
    }

    public function elementPageContent() {
        return $this->elementMessages()
            . element::br()
            . $this->elementProductEditForm();
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_PRODUCTS;
    }

    /**
     * @param $product BuyNowProduct
     * @return string
     */
    private function elementProductEditForm() {
        $formHRO = HROFactory::fromRegistry()->createFormBuilder()
            ->setId($this->isEditMode() ? AdminViewFieldsBuyNow::PRODUCT_EDIT_FORM : AdminViewFieldsBuyNow::PRODUCT_ADD_FORM)
            ->setAction(RedirectServiceBuyNow::productList())
            ->setManagedFields($this->productFields)
            ->addButtonSave()
            ->addButtonCancel(RedirectServiceBuyNow::productList());
        if ($this->isEditMode()) {
            $formHRO->addButtonDelete(RedirectServiceBuyNow::productDelete($this->product->getId()));
            $formHRO->addHiddenInput(RequestParamsBuyNow::PRODUCT_ID, $this->product->getId());
        }
        return $formHRO->build();
    }

    public function getFormFields() {
        return $this->productFields;
    }

    public function isEditMode() {
        return $this->product != null && $this->product->getId() != null;
    }

    public static function builder() {
        return new AdminBuyNowProductViewPage();
    }
}