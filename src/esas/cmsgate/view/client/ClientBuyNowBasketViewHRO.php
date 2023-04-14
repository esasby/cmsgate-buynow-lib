<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 24.06.2019
 * Time: 14:11
 */

namespace esas\cmsgate\view\client;

use esas\cmsgate\buynow\BuyNowBasket;
use esas\cmsgate\utils\htmlbuilder\hro\HRO;
use esas\cmsgate\utils\htmlbuilder\page\AdditionalCssPage;
use esas\cmsgate\utils\htmlbuilder\page\SingleFormPage;

interface ClientBuyNowBasketViewHRO extends HRO, SingleFormPage, AdditionalCssPage
{
    /**
     * @param $basket BuyNowBasket
     * @return ClientBuyNowBasketViewHRO
     */
    public function setBasket($basket);
}