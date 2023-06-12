<?php


namespace esas\cmsgate\buynow\hro\admin;



use esas\cmsgate\bridge\service\SessionServiceBridge;
use esas\cmsgate\buynow\dao\BasketItemBuyNow;
use esas\cmsgate\buynow\dao\ProductBuyNow;
use esas\cmsgate\buynow\dao\ProductBuyNowRepository;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\buynow\view\admin\AdminViewFieldsBuyNow;
use esas\cmsgate\hro\forms\FormHROFactory;
use esas\cmsgate\utils\htmlbuilder\page\AddOrUpdatePage;
use esas\cmsgate\view\admin\fields\ConfigFieldList;
use esas\cmsgate\view\admin\fields\ConfigFieldNumber;
use esas\cmsgate\view\admin\fields\ListOption;
use esas\cmsgate\view\admin\ManagedFields;
use esas\cmsgate\view\admin\validators\ValidatorNumeric;

class AdminBuyNowBasketItemViewPage extends AdminBuyNowPage implements AddOrUpdatePage
{
    /**
     * @var BasketItemBuyNow
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
     * @param BasketItemBuyNow $basketItem
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
        $formHRO = FormHROFactory::findBuilder()
            ->setId($this->isEditMode() ? AdminViewFieldsBuyNow::BASKET_ITEM_EDIT_FORM : AdminViewFieldsBuyNow::BASKET_ITEM_ADD_FORM)
            ->setAction(RedirectServiceBuyNow::fromRegistry()->basketItemAdd($this->basketItem->getBasketId()))
            ->setManagedFields($this->basketItemFields)
            ->addButtonSave()
            ->addButtonCancel(RedirectServiceBuyNow::fromRegistry()->basketEdit($this->basketItem->getBasketId()));
        if ($this->isEditMode()) {
            $formHRO->addButtonDelete(RedirectServiceBuyNow::fromRegistry()->basketItemDelete($this->basketItem->getBasketId(), $this->basketItem->getId()));
            $formHRO->addHiddenInput(RequestParamsBuyNow::BASKET_ITEM_ID, $this->basketItem->getId());

        }
        return $formHRO->build();
    }

    /**
     * @return ListOption[]
     */
    public function getProductsList() {
        $options = array();
        /** @var ProductBuyNow[] $products */
        $products = ProductBuyNowRepository::fromRegistry()->getByMerchantId(SessionServiceBridge::fromRegistry()->getMerchantUUID());
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