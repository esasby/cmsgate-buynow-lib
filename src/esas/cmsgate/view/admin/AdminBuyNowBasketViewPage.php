<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\bridge\ShopConfigBuyNow;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowBasket;
use esas\cmsgate\buynow\BuyNowBasketItem;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactory;
use esas\cmsgate\utils\htmlbuilder\page\AddOrUpdatePage;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldList;
use esas\cmsgate\view\admin\fields\ConfigFieldText;
use esas\cmsgate\view\admin\fields\ConfigFieldTextarea;
use esas\cmsgate\view\admin\fields\ListOption;
use esas\cmsgate\view\admin\validators\ValidatorNotEmpty;
use esas\cmsgate\view\RedirectServiceBuyNow;

class AdminBuyNowBasketViewPage extends AdminBuyNowPage implements AddOrUpdatePage
{
    /**
     * @var BuyNowBasket
     */
    private $basket;
    /**
     * @var ManagedFields
     */
    private $basketFields;

    /**
     * @var BuyNowBasketItem[]
     */
    private $basketItems;

    /**
     * AdminBuyNowBasketViewPage constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->basketFields = new ManagedFields();
        $this->basketFields
            ->addField(new ConfigFieldText(RequestParamsBuyNow::BASKET_NAME, 'Name', '', true, new ValidatorNotEmpty(), false))
            ->addField(new ConfigFieldTextarea(RequestParamsBuyNow::BASKET_DESCRIPTION, 'Description', '', true, null, 20, 4))
            ->addField(new ConfigFieldList(RequestParamsBuyNow::BASKET_SHOP_CONFIG_ID, 'Shop config', '', true, $this->getShopConfigsList()))
            ->addField(new ConfigFieldCheckbox(RequestParamsBuyNow::BASKET_ACTIVE, 'Active?', '', true, null))
            ->addField(new ConfigFieldCheckbox(RequestParamsBuyNow::BASKET_ASK_NAME, 'Ask name?', '', true, null))
            ->addField(new ConfigFieldCheckbox(RequestParamsBuyNow::BASKET_ASK_EMAIL, 'Ask email?', '', true, null))
            ->addField(new ConfigFieldCheckbox(RequestParamsBuyNow::BASKET_ASK_PHONE, 'Ask phone?', '', true, null));
    }

    /**
     * @param BuyNowBasket $basket
     * @return AdminBuyNowBasketViewPage
     */
    public function setBasket($basket) {
        $this->basket = $basket;
        if ($this->basket != null) {
            $this->basketFields->getField(RequestParamsBuyNow::BASKET_NAME)->setValue($basket->getName());
            $this->basketFields->getField(RequestParamsBuyNow::BASKET_DESCRIPTION)->setValue($basket->getDescription());
            $this->basketFields->getField(RequestParamsBuyNow::BASKET_SHOP_CONFIG_ID)->setValue($basket->getShopConfigId());
            $this->basketFields->getField(RequestParamsBuyNow::BASKET_ACTIVE)->setValue($basket->isActive());
            $this->basketFields->getField(RequestParamsBuyNow::BASKET_ASK_NAME)->setValue($basket->isAskFIO());
            $this->basketFields->getField(RequestParamsBuyNow::BASKET_ASK_EMAIL)->setValue($basket->isAskEmail());
            $this->basketFields->getField(RequestParamsBuyNow::BASKET_ASK_PHONE)->setValue($basket->isAskPhone());
        }
        if ($this->basket->getId() != null) {
            $this->basketItems = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->getByBasketId($basket->getId());
        }
        return $this;
    }


    public function elementPageContent() {
        return
            $this->elementMessages()
            . element::br()
            . $this->elementBasketEditForm()
            . $this->elementBasketItemsPanel();
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_BASKETS;
    }

    /**
     * @param $basket BuyNowBasket
     * @return string
     */
    private function elementBasketEditForm() {
        $formHRO = HROFactory::fromRegistry()->createFormBuilder()
            ->setId($this->isEditMode() ? AdminViewFieldsBuyNow::BASKET_EDIT_FORM : AdminViewFieldsBuyNow::BASKET_ADD_FORM)
            ->setAction(RedirectServiceBuyNow::basketList())
            ->setManagedFields($this->basketFields)
            ->addButtonSave()
            ->addButtonCancel(RedirectServiceBuyNow::basketList());
        if ($this->isEditMode()) {
            $formHRO->addButtonDelete(RedirectServiceBuyNow::basketDelete($this->basket->getId()));
            $formHRO->addHiddenInput(RequestParamsBuyNow::BASKET_ID, $this->basket->getId());
        }
        return $formHRO->build();
    }

    /**
     * @return ListOption[]
     */
    public function getShopConfigsList() {
        $options = array();
        /** @var ShopConfigBuyNow[] $shopConfigs */
        $shopConfigs = BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository()->getByMerchantId(SessionUtilsBridge::getMerchantUUID());
        foreach ($shopConfigs as $shopConfig) {
            $options[] = new ListOption($shopConfig->getUuid(), $shopConfig->getName());
        }
        return $options;
    }

    public function getFormFields() {
        return $this->basketFields;
    }

    protected function elementBasketItemsPanel() {
        if ($this->basket->getId() == null)
            return '';
        return element::br() .
            HROFactory::fromRegistry()->createDataListBuilder()
                ->setMainLabel(AdminViewFieldsBuyNow::BASKET_ITEM_LIST)
                ->setTableHeaderColumns(['Id', 'Product', 'Count', 'Max count', 'Created At'])
                ->setTableBody($this->elementBasketItemsTableBody())
                ->addFooterButtonAdd(RedirectServiceBuyNow::basketItemAdd($this->basket->getId()))
                ->build();
    }

    public function elementBasketItemsTableBody() {
        $rows = '';
        foreach ($this->basketItems as $key => $value) {
            $rows .= $this->elementBasketItemsTableRow($value, $key);
        }
        return element::tbody($rows);
    }

    /**
     * @param BuyNowBasketItem $basketItem
     */
    public function elementBasketItemsTableRow($basketItem, $rowId) {
        return element::tr(
            attribute::clazz("position-relative"),
            element::td(TablePreset::elementTdStretchedLink($basketItem->getId(), RedirectServiceBuyNow::basketItemEdit($basketItem->getBasketId(), $basketItem->getId()))),
            element::td($basketItem->getProduct()->getName()),
            element::td($basketItem->getCount()),
            element::td($basketItem->getMaxCount()),
            element::td($basketItem->getCreatedAt())
        );
    }

    public static function builder() {
        return new AdminBuyNowBasketViewPage();
    }

    public function isEditMode() {
        return $this->basket != null && $this->basket->getId() != null;
    }
}