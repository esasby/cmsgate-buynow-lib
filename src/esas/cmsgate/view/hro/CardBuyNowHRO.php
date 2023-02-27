<?php


namespace esas\cmsgate\view\hro;


use esas\cmsgate\utils\htmlbuilder\hro\cards\CardHRO;

class CardBuyNowHRO extends CardHRO
{
    public static function builder() {
        return new CardBuyNowHRO();
    }
}