<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 24.06.2019
 * Time: 14:11
 */

namespace esas\cmsgate\buynow\hro\client;

use esas\cmsgate\buynow\dao\BasketBuyNow;
use esas\cmsgate\hro\HRO;
use esas\cmsgate\utils\htmlbuilder\page\AdditionalCssPage;
use esas\cmsgate\utils\htmlbuilder\page\SingleFormPage;

interface ClientBuyNowBasketViewHRO extends HRO, SingleFormPage, AdditionalCssPage
{
    /**
     * @param $basket BasketBuyNow
     * @return ClientBuyNowBasketViewHRO
     */
    public function setBasket($basket);

    public function setAccessible($isAccessible);
}