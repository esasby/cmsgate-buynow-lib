<?php


namespace esas\cmsgate\view;


use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactory;
use esas\cmsgate\view\client\ClientBuyNowBasketViewHRO;
use esas\cmsgate\view\client\ClientBuyNowBasketViewHRO_v1;
use Exception;

class HROFactoryBuyNow implements HROFactory
{
    private static $instance;

    /**
     * @return HROFactoryBuyNow
     */
    public static function getInstance() {
        if (self::$instance == null)
            self::$instance = new HROFactoryBuyNow();
        return self::$instance;
    }

    /**
     * @return HROFactoryBuyNow
     */
    public static function fromRegistry() {

        try {
            $hroFactory = Registry::getRegistry()->getService(HROFactoryBuyNow::class);
        } catch (Exception $e) {
            $hroFactory = new HROFactoryBuyNow();
        }
        return $hroFactory;
    }

    /**
     * @return ClientBuyNowBasketViewHRO
     */
    public function createClientBasketViewPage() {
        return ClientBuyNowBasketViewHRO_v1::builder();
    }
}