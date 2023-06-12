<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 24.06.2019
 * Time: 14:11
 */

namespace esas\cmsgate\buynow\hro\client;

use esas\cmsgate\hro\HRO;
use esas\cmsgate\utils\htmlbuilder\page\AdditionalCssPage;

interface ClientBuyNowHomeHRO extends HRO, AdditionalCssPage {
    /**
     * @param mixed $pageHeaderText
     * @return ClientBuyNowHomeHRO
     */
    public function setPageHeaderText($pageHeaderText);

    /**
     * @param mixed $pageHeaderDetailsText
     * @return ClientBuyNowHomeHRO
     */
    public function setPageHeaderDetailsText($pageHeaderDetailsText);

    /**
     * @param mixed $bodyText
     * @return ClientBuyNowHomeHRO
     */
    public function setBodyText($bodyText);

    /**
     * @param $screenShot mixed
     * @return ClientBuyNowHomeHRO
     */
    public function addScreenShot($screenShot) ;
}