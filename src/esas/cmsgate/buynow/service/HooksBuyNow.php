<?php


namespace esas\cmsgate\buynow\service;


use esas\cmsgate\controllers\Controller;
use esas\cmsgate\hro\pages\ClientOrderCompletionPageHRO;
use esas\cmsgate\Registry;
use esas\cmsgate\service\Service;
use esas\cmsgate\wrappers\OrderWrapper;

abstract class HooksBuyNow extends Service
{

    /**
     * @return HooksBuyNow
     */
    public static function fromRegistry() {
        return Registry::getRegistry()->getService(HooksBuyNow::class);
    }

    /**
     * @param $order OrderWrapper
     * @param ClientOrderCompletionPageHRO $completionPageBuilder
     * @return Controller
     */
    public abstract function onOrderDisplay($orderWrapper, &$completionPageBuilder);
}