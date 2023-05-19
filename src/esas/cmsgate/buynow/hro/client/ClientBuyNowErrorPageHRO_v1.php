<?php


namespace esas\cmsgate\buynow\hro\client;


use esas\cmsgate\buynow\view\client\ClientViewFieldsBuyNow;
use esas\cmsgate\lang\Translator;

class ClientBuyNowErrorPageHRO_v1 extends ClientBuyNowPage implements ClientBuyNowErrorPageHRO
{
    public static function builder() {
        return new ClientBuyNowErrorPageHRO_v1();
    }

    public function getElementSectionHeaderTitle() {
        return Translator::fromRegistry()->translate(ClientViewFieldsBuyNow::BASKET_PAGE_HEADER);
    }

    public function getElementSectionHeaderDetails() {
        return Translator::fromRegistry()->translate(ClientViewFieldsBuyNow::BASKET_PAGE_HEADER_DETAILS);
    }

    public function elementPageContent() {
        return "";
    }
}