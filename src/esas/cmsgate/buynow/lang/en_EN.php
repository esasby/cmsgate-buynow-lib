<?php

use esas\cmsgate\bridge\dao\OrderStatusBridge;
use esas\cmsgate\buynow\messenger\MessagesBuyNow;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\buynow\view\admin\AdminViewFieldsBuyNow;
use esas\cmsgate\buynow\view\client\ClientViewFieldsBuyNow;

return array(
    AdminViewFieldsBuyNow::MENU_SHOP_CONFIGS => "Shop configs",
    AdminViewFieldsBuyNow::MENU_PRODUCTS => "Products",
    AdminViewFieldsBuyNow::MENU_BASKETS => "Baskets",
    AdminViewFieldsBuyNow::MENU_ORDERS => "Orders",
    AdminViewFieldsBuyNow::PRODUCT_LIST => "Products list",
    AdminViewFieldsBuyNow::PRODUCT_ADD_FORM => "Add product",
    AdminViewFieldsBuyNow::PRODUCT_EDIT_FORM => "Edit product",
    AdminViewFieldsBuyNow::PRODUCT_LINKED_BASKET_LIST => "Used in baskets",
    AdminViewFieldsBuyNow::BASKET_LIST => "Baskets list",
    AdminViewFieldsBuyNow::BASKET_ADD_FORM => "Add basket",
    AdminViewFieldsBuyNow::BASKET_EDIT_FORM => "Edit basket",
    AdminViewFieldsBuyNow::BASKET_ITEM_LIST => "Items list",
    AdminViewFieldsBuyNow::BASKET_ITEM_ADD_FORM => "Add basket product",
    AdminViewFieldsBuyNow::BASKET_ITEM_EDIT_FORM => "Edit basket product",
    AdminViewFieldsBuyNow::SHOP_CONFIG_LIST => "Shops list",
    AdminViewFieldsBuyNow::SHOP_CONFIG_EDIT_FORM => "Edit shop config",
    AdminViewFieldsBuyNow::SHOP_CONFIG_ADD_FORM => "Add shop config",
    AdminViewFieldsBuyNow::SHOP_CONFIG_LINKED_BASKET_LIST => "Linked baskets",
    AdminViewFieldsBuyNow::ORDER_LIST => "Orders list",
    AdminViewFieldsBuyNow::ORDER_VIEW_FORM => "Order details",
    AdminViewFieldsBuyNow::CLIENT_BASKET_LINK => "Buy now! link",
    AdminViewFieldsBuyNow::CLIENT_ORDER_LINK => "Client order link",
    OrderStatusBridge::PENDING => "Pending payment",
    OrderStatusBridge::PAYED => "Payed",
    OrderStatusBridge::CANCELED => "Canceled",
    OrderStatusBridge::FAILED => "Failed",
    RequestParamsBuyNow::SHOP_CONFIG_NAME => "Config name",
    RequestParamsBuyNow::CUSTOMER_EMAIL => 'Email',
    RequestParamsBuyNow::CUSTOMER_FIO => 'Full name',
    RequestParamsBuyNow::CUSTOMER_PHONE => 'Phone number',
    RequestParamsBuyNow::BASKET_MAX_PAID_COUNT => 'Payment limit',
    RequestParamsBuyNow::BASKET_EXPIRES_AT => 'Expires at',
    RequestParamsBuyNow::BASKET_ASK_NAME => 'Ask name?',
    RequestParamsBuyNow::BASKET_ASK_EMAIL => 'Ask email?',
    RequestParamsBuyNow::BASKET_ASK_PHONE => 'Ask phone?',
    RequestParamsBuyNow::BASKET_ACTIVE => 'Active?',
    MessagesBuyNow::BASKET_INCORRECT_ID => 'Incorrect basket id',
    MessagesBuyNow::BASKET_IS_INACTIVE => 'Basket is inactive',
    MessagesBuyNow::BASKET_IS_EXPIRED => 'Basket is expired',
    MessagesBuyNow::BASKET_LIMIT_REACHED => 'Basket payment limit reached',
    ClientViewFieldsBuyNow::BASKET_PAGE_HEADER => 'Ordering',
    ClientViewFieldsBuyNow::BASKET_PAGE_HEADER_DETAILS => 'Please check data and confirm order',
    ClientViewFieldsBuyNow::HOME_PAGE_HEADER => 'BuyNow Epos',
    ClientViewFieldsBuyNow::HOME_PAGE_HEADER_DETAILS => 'Accept QR-payments simply',
    ClientViewFieldsBuyNow::HOME_PAGE_BUY_NOW_DESCRIPTION => '<p><strong>BuyNow EPOS</strong> - simple integration for Your site with EPOS payment system!</p>
<ul>
<li>No need for moving to e-commerce CMS systems</li> 
<li>Can be used for selling with social networks</li>
</ul> 
<p>BuyNow EPOS gives You possibility to generate unique link, which can be add everywhere on You site</p>',
);