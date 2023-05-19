<?php


namespace esas\cmsgate\buynow\hro\client;


use esas\cmsgate\buynow\dao\BasketBuyNow;
use esas\cmsgate\buynow\dao\BasketItemBuyNow;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\buynow\service\ResourceServiceBuyNow;
use esas\cmsgate\buynow\view\client\ClientViewFieldsBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\hro\cards\CardHROFactory;
use esas\cmsgate\hro\carousels\CarouselHROFactory;
use esas\cmsgate\hro\carousels\CarouselItemHROFactory;
use esas\cmsgate\hro\forms\FormFieldTextHROFactory;
use esas\cmsgate\hro\shop\BasketItemHROFactory;
use esas\cmsgate\hro\shop\BasketItemListHROFactory;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\presets\BootstrapPreset as bootstrap;
use esas\cmsgate\utils\htmlbuilder\presets\ScriptsPreset;
use esas\cmsgate\view\admin\fields\ConfigFieldText;
use esas\cmsgate\view\admin\ManagedFields;
use esas\cmsgate\view\admin\validators\ValidatorEmail;
use esas\cmsgate\view\admin\validators\ValidatorNotEmpty;

class ClientBuyNowHomeHRO_v1 extends ClientBuyNowPage implements ClientBuyNowHomeHRO
{
    public static function builder() {
        return new ClientBuyNowHomeHRO_v1();
    }

    public function getElementSectionHeaderTitle() {
        return Translator::fromRegistry()->translate(ClientViewFieldsBuyNow::HOME_PAGE_HEADER);
    }

    public function getElementSectionHeaderDetails() {
        return Translator::fromRegistry()->translate(ClientViewFieldsBuyNow::HOME_PAGE_HEADER_DETAILS);
    }

    public function elementPageContent() {
        return
            element::content(Translator::fromRegistry()->translate(ClientViewFieldsBuyNow::HOME_PAGE_BUY_NOW_DESCRIPTION))
            . element::div(
                attribute::clazz("container bg-light p-3 border rounded"),
                $this->elementScreenCarousel())
            . element::br();
    }

    public function elementPageErrorContent() {
        return $this->elementPageContent();
    }

    public function elementScreenCarousel() {
        $itemExtraClass = "bg-light p-5";
        return CarouselHROFactory::findBuilder()
            ->setId("buyNowScreensCarousel")
            ->showIndicators()
            ->showDark()
            ->addItem(
                CarouselItemHROFactory::findBuilder()
                    ->setImage(ResourceServiceBuyNow::getClientScr(1))
                    ->setExtClass($itemExtraClass)
                    ->setActive()
                    ->build())
            ->addItem(
                CarouselItemHROFactory::findBuilder()
                    ->setImage(ResourceServiceBuyNow::getClientScr(2))
                    ->setExtClass($itemExtraClass)
                    ->build())
            ->addItem(
                CarouselItemHROFactory::findBuilder()
                    ->setImage(ResourceServiceBuyNow::getClientScr(3))
                    ->setExtClass($itemExtraClass)
                    ->build())
            ->addItem(
                CarouselItemHROFactory::findBuilder()
                    ->setImage(ResourceServiceBuyNow::getClientScr(4))
                    ->setExtClass($itemExtraClass)
                    ->build())
            ->build();
    }
}