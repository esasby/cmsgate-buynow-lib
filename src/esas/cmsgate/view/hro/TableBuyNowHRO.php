<?php


namespace esas\cmsgate\view\hro;


use esas\cmsgate\utils\htmlbuilder\hro\tables\TableHRO;

class TableBuyNowHRO extends TableHRO
{
    public static function builder() {
        return new TableBuyNowHRO();
    }
}