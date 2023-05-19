<?php
namespace esas\cmsgate\buynow\controllers\client;

use esas\cmsgate\buynow\dao\BasketBuyNow;
use esas\cmsgate\buynow\hro\client\ClientBuyNowErrorPageHROFactory;
use esas\cmsgate\controllers\Controller;
use esas\cmsgate\buynow\properties\PropertiesBuyNow;
use Exception;
use Throwable;

class ClientControllerBuyNow extends Controller
{
    const PATTERN_BASKET_VIEW = '/.*\/baskets\/(?<basketId>.+)$/';
    const PATTERN_ORDER_VIEW = '/.*\/orders\/(?<orderId>.+)$/';

    public function process() {
        try {
            $request = $_SERVER['REDIRECT_URL'];
            $controller = null;
            if (preg_match(self::PATTERN_BASKET_VIEW, $request, $pathParams)) {
                $controller = new ClientControllerBuyNowBasket($pathParams['basketId']);
            } elseif (preg_match(self::PATTERN_ORDER_VIEW, $request, $pathParams)) {
                $controller = new ClientControllerBuyNowOrder($pathParams['orderId']);
            } else {
                $controller = new ClientControllerBuyHome();
            }
            $controller->process();
        } catch (Throwable $e) {
            ClientBuyNowErrorPageHROFactory::findBuilder()
                ->addCssLink($this->getClientUICssLink())
                ->render();
        } catch (Exception $e) {
            ClientBuyNowErrorPageHROFactory::findBuilder()
                ->addCssLink($this->getClientUICssLink())
                ->render();
        }
    }

    /**
     * @param $basket BasketBuyNow
     * @return mixed
     */
    public function getClientUICssLink($basket = null) {
        if (!empty($basket) && !empty($basket->getClientUICss()))
            return $basket->getClientUICss();
        else
            return PropertiesBuyNow::fromRegistry()->getDefaultClientUICssLink();
    }
}