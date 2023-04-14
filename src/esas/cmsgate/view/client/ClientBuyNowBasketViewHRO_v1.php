<?php


namespace esas\cmsgate\view\client;

use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowBasket;
use esas\cmsgate\buynow\BuyNowBasketItem;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactoryCmsGate;
use esas\cmsgate\utils\htmlbuilder\presets\BootstrapPreset as bootstrap;
use esas\cmsgate\utils\htmlbuilder\presets\ScriptsPreset;
use esas\cmsgate\view\admin\fields\ConfigFieldText;
use esas\cmsgate\view\admin\ManagedFields;
use esas\cmsgate\view\admin\validators\ValidatorEmail;
use esas\cmsgate\view\admin\validators\ValidatorNotEmpty;
use esas\cmsgate\view\RedirectServiceBuyNow;

class ClientBuyNowBasketViewHRO_v1 extends ClientBuyNowPage implements ClientBuyNowBasketViewHRO
{
    /**
     * @var BuyNowBasket
     */
    protected $basket;

    /**
     * @var BuyNowBasketItem[]
     */
    private $basketItems;

    /**
     * @var ManagedFields
     */
    protected $managedFields;

    /**
     * @inheritDoc
     */
    public function setBasket($basket) {
        $this->basket = $basket;
        if ($this->basket->getId() != null) {
            $this->basketItems = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketItemRepository()->getByBasketIdOnlyActiveProducts($basket->getId());
        }
        $this->managedFields = new ManagedFields();
        if ($this->basket->isAskFIO())
            $this->managedFields->addField(new ConfigFieldText(RequestParamsBuyNow::CUSTOMER_FIO, Translator::fromRegistry()->translate(RequestParamsBuyNow::CUSTOMER_FIO), '', true, new ValidatorNotEmpty(), false));
        if ($this->basket->isAskPhone())
            $this->managedFields->addField(new ConfigFieldText(RequestParamsBuyNow::CUSTOMER_PHONE, Translator::fromRegistry()->translate(RequestParamsBuyNow::CUSTOMER_PHONE), '', true, new ValidatorNotEmpty(), false));
        if ($this->basket->isAskEmail())
            $this->managedFields->addField(new ConfigFieldText(RequestParamsBuyNow::CUSTOMER_EMAIL, Translator::fromRegistry()->translate(RequestParamsBuyNow::CUSTOMER_EMAIL), '', true, new ValidatorEmail(), false));
        return $this;
    }

    public static function builder() {
        return new ClientBuyNowBasketViewHRO_v1();
    }

    public function getElementSectionHeaderTitle() {
        return Translator::fromRegistry()->translate(ClientViewFieldsBuyNow::BASKET_PAGE_HEADER);
    }

    public function getElementSectionHeaderDetails() {
        return Translator::fromRegistry()->translate(ClientViewFieldsBuyNow::BASKET_PAGE_HEADER_DETAILS);
    }

    public function elementPageContent() {
        return
            $this->elementBasketDescription() .
            element::form(
                attribute::action(RedirectServiceBuyNow::clientBasketConfirm($this->basket->getId())),
                attribute::method("post"),
                attribute::enctype("multipart/form-data"),
                bootstrap::elementInputHidden(RequestParamsBuyNow::BASKET_ID, $this->basket->getId()),
                $this->elementFormBody()
            );
    }

    public function elementPageErrorContent() {
        return $this->elementPageContent();
    }

    protected function elementBasketDescription() {
        if ($this->basket->getDescription() == null)
            return '';
        return
            element::p(
                attribute::clazz('lead'),
                $this->basket->getDescription()
            );
    }

    protected function elementFormBody() {
        return
            bootstrap::elementRowExt("gy-3",
                bootstrap::elementDiv("col-md-8",
                    HROFactoryCmsGate::fromRegistry()->createCardBuilder()
                        ->setCardHeader('Товары')
                        ->setCardBody($this->elementBasketItems())
                        ->build()),
                bootstrap::elementDiv("col-md-4",
                    HROFactoryCmsGate::fromRegistry()->createCardBuilder()
                        ->setCardHeader('Персональная информация')
                        ->setCardBody($this->elementCustomerDetails())
                        ->build())
            )
            . element::br()
            . bootstrap::elementRow(
                bootstrap::elementDiv("col-md-2",
                    bootstrap::elementAButton(Translator::fromRegistry()->translate(ClientViewFieldsBuyNow::BACK), $this->basket->getReturnUrl(), 'btn-outline-secondary col-12')),
                bootstrap::elementCol(),
                bootstrap::elementDiv("col-md-2",
                    bootstrap::elementRow(
                        element::p(attribute::clazz("col-4 col-form-label"), 'Итого: '),
                        element::h5(attribute::clazz("col col-form-label"), element::span(
                                attribute::id('basket_total'),
                                $this->calcTotal()
                            ) . ' BYN')
                    )
                ),
                bootstrap::elementDiv("col-md-2",
                    bootstrap::elementButtonSubmit(Translator::fromRegistry()->translate(ClientViewFieldsBuyNow::PLACE_ORDER), 'btn-success col-12')
                ))
            . element::br()
            . ScriptsPreset::elementScriptBasketTotal();
    }

    public function calcTotal() {
        $total = 0;
        foreach ($this->basketItems as $basketItem) {
            $total += $basketItem->getProduct()->getPrice() * $basketItem->getCount();
        }
        return $total;
    }

    public function elementBasketItems() {
        $basketItemListBuilder = HROFactoryCmsGate::fromRegistry()->createBasketItemListBuilder();
        foreach ($this->basketItems as $basketItem) {
            $basketItemListBuilder->addItem(HROFactoryCmsGate::fromRegistry()->createBasketItemBuilder()
                ->setProductId($basketItem->getProductId())
                ->setProductName($basketItem->getProduct()->getName())
                ->setProductSKU($basketItem->getProduct()->getSku())
                ->setProductDescription($basketItem->getProduct()->getDescription())
                ->setCount($basketItem->getCount())
                ->setCountInputId(RequestParamsBuyNow::getBasketProductCountInputId($basketItem->getProductId()))
                ->setMaxCount($basketItem->getMaxCount())
                ->setPrice($basketItem->getProduct()->getPrice())
                ->setCurrency($basketItem->getProduct()->getCurrency())
                ->setImage($basketItem->getProduct()->getImage())
                ->build());
        }
        return $basketItemListBuilder->build();
    }

    public function elementCustomerDetails() {
        $elementCustomerDetails = '';
        foreach ($this->managedFields->getFieldsToRender() as $fieldDescriptor) {
            $elementCustomerDetails .= HROFactoryCmsGate::fromRegistry()->createFormFieldTextBuilder()
                ->setFieldDescriptor($fieldDescriptor)
                ->setOneRow(false)
                ->build();
        }
        return
            element::div(
                $elementCustomerDetails
            );
    }


    public function getFormFields() {
        return $this->managedFields;
    }
}