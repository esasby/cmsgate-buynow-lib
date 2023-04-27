<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 24.06.2019
 * Time: 14:11
 */

namespace esas\cmsgate\buynow\hro\client;

use esas\cmsgate\hro\pages\ClientPageHRO;

abstract class ClientBuyNowPage extends ClientPageHRO
{
    public function getPageTitle() {
        return "BuyNow";
    }
}