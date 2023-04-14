<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\buynow\BuyNowBasket;
use esas\cmsgate\buynow\BuyNowProduct;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactoryCmsGate;
use esas\cmsgate\utils\htmlbuilder\page\AddOrUpdatePage;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;
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
     * @var BuyNowBasket[]
     */
    private $linkedBaskets;

    /**
     * AdminBuyNowProductViewPage constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->productFields = new ManagedFields();
        $this->productFields
            ->addField(new ConfigFieldText(RequestParamsBuyNow::PRODUCT_SKU, 'SKU', '', true, new ValidatorNotEmpty(), false))
            ->addField(new ConfigFieldText(RequestParamsBuyNow::PRODUCT_NAME, 'Name', '', true, new ValidatorNotEmpty(), false))
            ->addField(new ConfigFieldText(RequestParamsBuyNow::PRODUCT_IMAGE, 'Image', '', true, new ValidatorNotEmpty(), false))
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
            $this->productFields->getField(RequestParamsBuyNow::PRODUCT_IMAGE)->setValue($this->product->getImage());
            $this->productFields->getField(RequestParamsBuyNow::PRODUCT_DESCRIPTION)->setValue($this->product->getDescription());
            $this->productFields->getField(RequestParamsBuyNow::PRODUCT_ACTIVE)->setValue($this->product->isActive());
            $this->productFields->getField(RequestParamsBuyNow::PRODUCT_PRICE)->setValue($this->product->getPrice());
            $this->productFields->getField(RequestParamsBuyNow::PRODUCT_CURRENCY)->setValue('BYN');
        }
        return $this;
    }

    /**
     * @param BuyNowBasket[] $linkedBaskets
     * @return AdminBuyNowProductViewPage
     */
    public function setLinkedBaskets($linkedBaskets) {
        $this->linkedBaskets = $linkedBaskets;
        return $this;
    }

    public function elementPageContent() {
        return $this->elementProductEditForm() . $this->elementLinkedBasketsPanel();
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_PRODUCTS;
    }

    /**
     * @param $product BuyNowProduct
     * @return string
     */
    private function elementProductEditForm() {
        $formHRO = HROFactoryCmsGate::fromRegistry()->createFormBuilder()
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

    protected function elementLinkedBasketsPanel() {
        if ($this->linkedBaskets == null)
            return '';
        return element::br() . element::br() .
            HROFactoryCmsGate::fromRegistry()->createDataListBuilder()
                ->setMainLabel(AdminViewFieldsBuyNow::PRODUCT_LINKED_BASKET_LIST)
                ->setTableHeaderColumns(['Id', 'Name', 'Active', 'Ask name', 'Ask phone', 'Ask email', 'Checkout counter', 'Created At'])
                ->setTableBody($this->elementLinkedBasketsTableBody())
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