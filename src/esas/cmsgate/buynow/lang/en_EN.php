<?php

use esas\cmsgate\bridge\dao\OrderStatusBridge;
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
    ClientViewFieldsBuyNow::BASKET_PAGE_HEADER => 'Ordering',
    ClientViewFieldsBuyNow::BASKET_PAGE_HEADER_DETAILS => 'Please check data and confirm order',
);