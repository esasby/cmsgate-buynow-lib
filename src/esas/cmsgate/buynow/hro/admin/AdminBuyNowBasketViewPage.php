<?php


namespace esas\cmsgate\buynow\hro\admin;


use esas\cmsgate\bridge\dao\ShopConfigRepository;
use esas\cmsgate\bridge\service\SessionServiceBridge;
use esas\cmsgate\buynow\dao\BasketBuyNow;
use esas\cmsgate\buynow\dao\BasketItemBuyNow;
use esas\cmsgate\buynow\dao\ShopConfigBuyNow;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\buynow\view\admin\AdminViewFieldsBuyNow;
use esas\cmsgate\hro\forms\FormHROFactory;
use esas\cmsgate\hro\panels\CopyToClipboardPanelHROFactory;
use esas\cmsgate\hro\tables\DataListHROFactory;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\page\AddOrUpdatePage;
use esas\cmsgate\utils\htmlbuilder\presets\BootstrapPreset as bootstrap;
use esas\cmsgate\utils\htmlbuilder\presets\ScriptsPreset;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldDateTime;
use esas\cmsgate\view\admin\fields\ConfigFieldList;
use esas\cmsgate\view\admin\fields\ConfigFieldNumber;
use esas\cmsgate\view\admin\fields\ConfigFieldText;
use esas\cmsgate\view\admin\fields\ConfigFieldTextarea;
use esas\cmsgate\view\admin\fields\ListOption;
use esas\cmsgate\view\admin\ManagedFields;
use esas\cmsgate\view\admin\validators\ValidatorDateTimeLocal;
use esas\cmsgate\view\admin\validators\ValidatorInteger;
use esas\cmsgate\view\admin\validators\ValidatorNotEmpty;

class AdminBuyNowBasketViewPage extends AdminBuyNowPage implements AddOrUpdatePage
{
    /**
     * @var BasketBuyNow
     */
    private $basket;
    /**
     * @var ManagedFields
     */
    private $basketFields;

    /**
     * @var BasketItemBuyNow[]
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
            ->addField(new ConfigFieldCheckbox(RequestParamsBuyNow::BASKET_ACTIVE, null, '', true, null))
            ->addField(new ConfigFieldNumber(RequestParamsBuyNow::BASKET_MAX_PAID_COUNT, null, '', true, new ValidatorInteger(0, 9999999), 0 , 9999999))
            ->addField(new ConfigFieldDateTime(RequestParamsBuyNow::BASKET_EXPIRES_AT, null, '', false, new ValidatorDateTimeLocal(true)))
            ->addField(new ConfigFieldCheckbox(RequestParamsBuyNow::BASKET_ASK_NAME, null, '', true, null))
            ->addField(new ConfigFieldCheckbox(RequestParamsBuyNow::BASKET_ASK_EMAIL, null, '', true, null))
            ->addField(new ConfigFieldCheckbox(RequestParamsBuyNow::BASKET_ASK_PHONE, null, '', true, null))
            ->addField(new ConfigFieldText(RequestParamsBuyNow::BASKET_RETURN_URL, 'Return url', '', true, new ValidatorNotEmpty()))
            ->addField(new ConfigFieldText(RequestParamsBuyNow::CLIENT_UI_CSS, 'UI css url', '', true, null));
    }

    public function elementPageHead() {
        $head = parent::elementPageHead();
        $head->add(ScriptsPreset::elementScriptCopyToClipboard());
        return $head;
    }

    /**
     * @param BasketBuyNow $basket
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
            $this->basketFields->getField(RequestParamsBuyNow::BASKET_RETURN_URL)->setValue($basket->getReturnUrl());
            $this->basketFields->getField(RequestParamsBuyNow::CLIENT_UI_CSS)->setValue($basket->getClientUICss());
            $this->basketFields->getField(RequestParamsBuyNow::BASKET_MAX_PAID_COUNT)->setValue($basket->getPaidMaxCount());
            if (!empty($basket->getExpiresAt()))
                $this->basketFields->getField(RequestParamsBuyNow::BASKET_EXPIRES_AT)->setValue($basket->getExpiresAt()->format('Y-m-d H:i'));
        }
        return $this;
    }

    /**
     * @param BasketItemBuyNow[] $basketItems
     * @return AdminBuyNowBasketViewPage
     */
    public function setBasketItems($basketItems) {
        $this->basketItems = $basketItems;
        return $this;
    }

    public function elementMessageAndContent() {
        return
            $this->elementClientLinkPanel()
            . $this->elementMessages()
            . $this->elementPageContent();
    }


    public function elementPageContent() {
        return
            $this->elementBasketEditForm()
            . $this->elementBasketItemsPanel();
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_BASKETS;
    }

    /**
     * @param $basket BasketBuyNow
     * @return string
     */
    private function elementBasketEditForm() {
        $formHRO = FormHROFactory::findBuilder()
            ->setId($this->isEditMode() ? AdminViewFieldsBuyNow::BASKET_EDIT_FORM : AdminViewFieldsBuyNow::BASKET_ADD_FORM)
            ->setAction(RedirectServiceBuyNow::fromRegistry()->basketList())
            ->setManagedFields($this->basketFields)
            ->addButtonSave()
            ->addButtonCancel(RedirectServiceBuyNow::fromRegistry()->basketList());
        if ($this->isEditMode()) {
            $formHRO->addButtonDelete(RedirectServiceBuyNow::fromRegistry()->basketDelete($this->basket->getId()));
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
        $shopConfigs = ShopConfigRepository::fromRegistry()->getByMerchantId(SessionServiceBridge::fromRegistry()->getMerchantUUID());
        foreach ($shopConfigs as $shopConfig) {
            $options[] = new ListOption($shopConfig->getUuid(), $shopConfig->getName());
        }
        return $options;
    }

    public function getFormFields() {
        return $this->basketFields;
    }

    protected function elementBasketItemsPanel() {
        if (!$this->isEditMode())
            return '';
        return element::br() .
            DataListHROFactory::findBuilder()
                ->setMainLabel(AdminViewFieldsBuyNow::BASKET_ITEM_LIST)
                ->setTableHeaderColumns(['Id', 'Product', 'Image', 'Price', 'Init Count', 'Max count', 'Created At', 'Actions'])
                ->setTableBody($this->elementBasketItemsTableBody())
                ->addFooterButtonAdd(RedirectServiceBuyNow::fromRegistry()->basketItemAdd($this->basket->getId()))
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
     * @param BasketItemBuyNow $basketItem
     */
    public function elementBasketItemsTableRow($basketItem, $rowId) {
        return element::tr(
            attribute::clazz("position-relative"),
//            element::td(TablePreset::elementTdStretchedLink($basketItem->getId(), RedirectServiceBuyNow::fromRegistry()->basketItemEdit($basketItem->getBasketId(), $basketItem->getId()))),
            element::td(bootstrap::elementAHrefNoDecoration($basketItem->getId(), RedirectServiceBuyNow::fromRegistry()->basketItemEdit($basketItem->getBasketId(), $basketItem->getId()))),
            element::td(bootstrap::elementAHrefNoDecoration($basketItem->getProduct()->getName(), RedirectServiceBuyNow::fromRegistry()->productEdit($basketItem->getProductId()))),
            element::td(element::img(
                attribute::src($basketItem->getProduct()->getImage()),
                attribute::clazz('img-thumbnail'),
                attribute::width('200')
            )),
            element::td($basketItem->getProduct()->getPrice()),
            element::td($basketItem->getCount()),
            element::td($basketItem->getMaxCount()),
            element::td($basketItem->getCreatedAt()),
            element::td(bootstrap::elementAButton(
                Translator::fromRegistry()->translate(AdminViewFields::DELETE),
                RedirectServiceBuyNow::fromRegistry()->basketItemDelete($basketItem->getBasketId(), $basketItem->getId()),
                'btn-outline-danger' ))
        );
    }

    public static function builder() {
        return new AdminBuyNowBasketViewPage();
    }

    public function isEditMode() {
        return $this->basket != null && $this->basket->getId() != null;
    }

    protected function elementClientLinkPanel() {
        if (!$this->isEditMode())
            return '';
        return CopyToClipboardPanelHROFactory::findBuilder()
            ->setLabelId(AdminViewFieldsBuyNow::CLIENT_BASKET_LINK)
            ->setValue(RedirectServiceBuyNow::fromRegistry()->clientBasketView($this->basket->getId()))
            ->build() . element::br();
    }
}