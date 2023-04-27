<?php


namespace esas\cmsgate\buynow\hro\admin;


use esas\cmsgate\buynow\service\RedirectServiceBuyNow;

class AdminBuyNowExceptionPage extends AdminBuyNowPage
{
    public function elementPageContent() {
        return "";
    }


    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_PRODUCTS;
    }

    public static function builder() {
        return new AdminBuyNowExceptionPage();
    }
}