<?php


namespace esas\cmsgate\buynow\service;


use esas\cmsgate\utils\ResourceUtils;

class ResourceServiceBuyNow extends ResourceUtils
{
    private static function getImageDir() {
        return dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/static/image/";
    }

    public static function getClientScr($id) {
        return self::getImageUrl(self::getImageDir(), 'client_scr_' . $id . '.png');
    }
}