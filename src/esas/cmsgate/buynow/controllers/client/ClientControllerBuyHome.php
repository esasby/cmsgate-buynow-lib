<?php


namespace esas\cmsgate\buynow\controllers\client;


use esas\cmsgate\buynow\hro\client\ClientBuyNowHomeHROFactory;
use esas\cmsgate\buynow\properties\PropertiesBuyNow;

class ClientControllerBuyHome extends ClientControllerBuyNow
{
    public function process() {
        ClientBuyNowHomeHROFactory::findBuilder()
            ->addCssLink(PropertiesBuyNow::fromRegistry()->getDefaultClientUICssLink())
            ->render();
    }
}