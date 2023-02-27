<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\bridge\ShopConfigBuyNow;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowBasket;
use esas\cmsgate\buynow\BuyNowBasketItem;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\cards\CardButtonsRowHRO;
use esas\cmsgate\utils\htmlbuilder\page\SingleFormPage;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldList;
use esas\cmsgate\view\admin\fields\ConfigFieldText;
use esas\cmsgate\view\admin\fields\ConfigFieldTextarea;
use esas\cmsgate\view\admin\fields\ListOption;
use esas\cmsgate\view\admin\validators\ValidatorNotEmpty;
use esas\cmsgate\view\hro\CardBuyNowHRO;
use esas\cmsgate\view\hro\TableBuyNowHRO;
use esas\cmsgate\view\RedirectServiceBuyNow;
use Exception;

class AdminBuyNowBasketViewPage extends AdminBuyNowPage implements SingleFormPage
{
    /**
     * @var BuyNowBasket
     */
    private $basket;
    /**
     * @var ConfigFormBridge
     */
    private $basketForm;

    /**
     * @var BuyNowBasketItem[]
     */
    private $basketItems;

    /**
     * AdminBuyNowBasketViewPage constructor.
     * @param $basket BuyNowBasket
     */
    public function __construct($basket) {
        parent::__construct();
        $this->basket = $basket;
        $this->basketForm = $this->createBasketEditForm($basket);
        if ($this->basket->getId() != null) {
            $this->basketItems = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->getByBasketId($basket->getId());
        }
    }

    public function elementPageContent() {
        return
            $this->elementMessages()
            . element::br()
            . $this->basketForm->generate()
            . $this->elementBasketItemsPanel();
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_BASKETS;
    }

    /**
     * @param $basket BuyNowBasket
     * @return ConfigFormBridge
     */
    private function createBasketEditForm($basket) {
        $managedFields = new ManagedFields();
        $fieldName
            = new ConfigFieldText(RequestParamsBuyNow::BASKET_NAME, 'Name', '', true, new ValidatorNotEmpty(), false);
        $fieldDescription
            = new ConfigFieldTextarea(RequestParamsBuyNow::BASKET_DESCRIPTION, 'Description', '', true, null, 20, 4);
        $fieldShopConfig
            = new ConfigFieldList(RequestParamsBuyNow::BASKET_SHOP_CONFIG_ID, 'Shop config', '', true, $this->getShopConfigsList());
        $fieldActive
            = new ConfigFieldCheckbox(RequestParamsBuyNow::BASKET_ACTIVE, 'Active?', '', true, null);
        $fieldAskName
            = new ConfigFieldCheckbox(RequestParamsBuyNow::BASKET_ASK_NAME, 'Ask name?', '', true, null);
        $fieldAskEmail
            = new ConfigFieldCheckbox(RequestParamsBuyNow::BASKET_ASK_EMAIL, 'Ask email?', '', true, null);
        $fieldAskPhone
            = new ConfigFieldCheckbox(RequestParamsBuyNow::BASKET_ASK_PHONE, 'Ask phone?', '', true, null);
        $managedFields->addField($fieldName->setValue($basket->getName()));
        $managedFields->addField($fieldDescription->setValue($basket->getDescription()));
        $managedFields->addField($fieldShopConfig->setValue($basket->getShopConfigId()));
        $managedFields->addField($fieldActive->setValue($basket->isActive()));
        $managedFields->addField($fieldAskName->setValue($basket->isAskFIO()));
        $managedFields->addField($fieldAskEmail->setValue($basket->isAskEmail()));
        $managedFields->addField($fieldAskPhone->setValue($basket->isAskPhone()));
        $formKey = $basket->getId() != null ? AdminViewFieldsBuyNow::LABEL_BASKET_EDIT : AdminViewFieldsBuyNow::LABEL_BASKET_ADD;
        $basketForm = new ConfigFormBridge($managedFields, $formKey, RedirectServiceBuyNow::basketList());
        if ($basket->getId() != null) {
            $basketForm->addFooterButtonDelete(RedirectServiceBuyNow::basketDelete($basket->getId()));
            $basketForm->addHiddenInput(RequestParamsBuyNow::BASKET_ID, $basket->getId());
        }
        $basketForm->addFooterButtonCancel(RedirectServiceBuyNow::basketList());
        return $basketForm;
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

    public function getForm() {
        return $this->basketForm;
    }

    protected function elementBasketItemsPanel() {
        if ($this->basket->getId() == null)
            return '';
        return element::br() .
            CardBuyNowHRO::builder()
                ->setCardHeaderI18n(AdminViewFieldsBuyNow::LABEL_BASKET_ITEM_LIST)
                ->setCardBody(TableBuyNowHRO::builder()
                    ->setTableHeaderColumns(['Id', 'Product', 'Count', 'Max count', 'Created At'])
                    ->setTableBody($this->elementBasketItemsTableBody())
                    ->build())
                ->setCardFooter(CardButtonsRowHRO::builder()
                    ->addButtonI18n(AdminViewFields::ADD, RedirectServiceBuyNow::basketItemAdd($this->basket->getId()), 'btn-secondary')
                    ->build()
                )
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
}