<?php
namespace esas\cmsgate\buynow\controllers\client;

use esas\cmsgate\buynow\dao\BasketBuyNow;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\buynow\properties\PropertiesBuyNow;

class ClientControllerBuyNow extends Controller
{
    const PATTERN_BASKET_VIEW = '/.*\/baskets\/(?<basketId>.+)$/';
    const PATTERN_ORDER_VIEW = '/.*\/orders\/(?<orderId>.+)$/';

    public function process() {
        $request = $_SERVER['REDIRECT_URL'];
        $controller = null;
        if (preg_match(self::PATTERN_BASKET_VIEW, $request, $pathParams)) {
            $controller = new ClientControllerBuyNowBasket($pathParams['basketId']);
        } elseif (preg_match(self::PATTERN_ORDER_VIEW, $request, $pathParams)) {
            $controller = new ClientControllerBuyNowOrder($pathParams['orderId']);
        } else {
            http_response_code(404);
        }
        $controller->process();
    }

    /**
     * @param $basket BasketBuyNow
     * @return mixed
     */
    public function getClientUICssLink($basket) {
        if (!empty($basket->getClientUICss()))
            return $basket->getClientUICss();
        else
            return PropertiesBuyNow::fromRegistry()->getDefaultClientUICssLink();
    }
}