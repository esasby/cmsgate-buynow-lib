<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\view\RedirectServiceBuyNow;

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