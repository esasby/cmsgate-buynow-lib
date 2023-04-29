<?php


namespace esas\cmsgate\buynow\controllers\client;


use esas\cmsgate\buynow\dao\OrderDataBuyNow;
use esas\cmsgate\buynow\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\hro\client\ClientBuyNowHomeHROFactory;
use esas\cmsgate\buynow\properties\PropertiesBuyNow;
use esas\cmsgate\epos\controllers\ControllerEposCompletionPanel;
use esas\cmsgate\epos\controllers\ControllerEposInvoiceAdd;
use esas\cmsgate\hro\pages\ClientOrderCompletionPageHROFactory;
use esas\cmsgate\Registry;
use esas\cmsgate\bridge\service\SessionServiceBridge;
use Exception;
use Throwable;

class ClientControllerBuyHome extends ClientControllerBuyNow
{
    public function process() {
        ClientBuyNowHomeHROFactory::findBuilder()
            ->addCssLink(PropertiesBuyNow::fromRegistry()->getDefaultClientUICssLink())
            ->render();
    }
}