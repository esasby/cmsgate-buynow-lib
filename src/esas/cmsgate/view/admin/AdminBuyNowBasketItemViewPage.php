<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowBasketItem;
use esas\cmsgate\buynow\BuyNowProduct;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactoryCmsGate;
use esas\cmsgate\utils\htmlbuilder\page\AddOrUpdatePage;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\view\admin\fields\ConfigFieldList;
use esas\cmsgate\view\admin\fields\ConfigFieldNumber;
use esas\cmsgate\view\admin\fields\ListOption;
use esas\cmsgate\view\admin\validators\ValidatorNumeric;
use esas\cmsgate\view\RedirectServiceBuyNow;

class AdminBuyNowBasketItemViewPage extends AdminBuyNowPage implements AddOrUpdatePage
{
    /**
     * @var BuyNowBasketItem
     */
    private $basketItem;

    /**
     * @var ManagedFields
     */
    private $basketItemFields;

    /**
     * AdminBuyNowBasketItemViewPage constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->basketItemFields = new ManagedFields();
        $this->basketItemFields
            ->addField(new ConfigFieldList(RequestParamsBuyNow::PRODUCT_ID, 'Product', '', true, $this->getProductsList()))
            ->addField(new ConfigFieldNumber(RequestParamsBuyNow::BASKET_ITEM_PRODUCT_COUNT, 'Initial Count', '', true, new ValidatorNumeric()))
            ->addField(new ConfigFieldNumber(RequestParamsBuyNow::BASKET_ITEM_PRODUCT_MAX_COUNT, 'Max Count', '', true, new ValidatorNumeric()));
    }

    public static function builder() {
        return new AdminBuyNowBasketItemViewPage();
    }

    /**
     * @param BuyNowBasketItem $basketItem
     * @return AdminBuyNowBasketItemViewPage
     */
    public function setBasketItem($basketItem) {
        $this->basketItem = $basketItem;
        if ($this->basketItem != null) {
            $this->basketItemFields->getField(RequestParamsBuyNow::PRODUCT_ID)->setValue($basketItem->getProductId());
            $this->basketItemFields->getField(RequestParamsBuyNow::BASKET_ITEM_PRODUCT_COUNT)->setValue($basketItem->getCount());
            $this->basketItemFields->getField(RequestParamsBuyNow::BASKET_ITEM_PRODUCT_MAX_COUNT)->setValue($basketItem->getMaxCount());
        }
        return $this;
    }

    public function elementPageContent() {
        return $this->elementBasketItemEditForm();
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_BASKETS;
    }

    /**
     * @return string
     */
    private function elementBasketItemEditForm() {
        $formHRO = HROFactoryCmsGate::fromRegistry()->createFormBuilder()
            ->setId($this->isEditMode() ? AdminViewFieldsBuyNow::BASKET_ITEM_EDIT_FORM : AdminViewFieldsBuyNow::BASKET_ITEM_ADD_FORM)
            ->setAction(RedirectServiceBuyNow::basketItemAdd($this->basketItem->getBasketId()))
            ->setManagedFields($this->basketItemFields)
            ->addButtonSave()
            ->addButtonCancel(RedirectServiceBuyNow::basketEdit($this->basketItem->getBasketId()));
        if ($this->isEditMode()) {
            $formHRO->addButtonDelete(RedirectServiceBuyNow::basketItemDelete($this->basketItem->getBasketId(), $this->basketItem->getId()));
            $formHRO->addHiddenInput(RequestParamsBuyNow::BASKET_ITEM_ID, $this->basketItem->getId());

        }
        return $formHRO->build();
    }

    /**
     * @return ListOption[]
     */
    public function getProductsList() {
        $options = array();
        /** @var BuyNowProduct[] $products */
        $products = BridgeConnectorBuyNow::fromRegistry()->getBuyNowProductRepository()->getByMerchantId(SessionUtilsBridge::getMerchantUUID());
        foreach ($products as $product) {
            $options[] = new ListOption($product->getId(), $product->getName() . ' #' . $product->getSku() . '');
        }
        return $options;
    }

    public function getFormFields() {
        return $this->basketItemFields;
    }

    public function isEditMode() {
        return $this->basketItem != null && $this->basketItem->getId() != null;
    }
}