<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 17.08.2018
 * Time: 11:09
 */

namespace esas\cmsgate\buynow\view\admin;

use esas\cmsgate\view\admin\AdminViewFields;

/**
 * Перечисление полей, доступных на странице настроек плагина
 * Class AdminViewFields
 * @package sas\cmsgate\view\admin
 */
class AdminViewFieldsBuyNow extends AdminViewFields
{
    const MENU_SHOP_CONFIGS = 'menu_shop_configs';
    const MENU_PRODUCTS = 'menu_products';
    const MENU_BASKETS = 'menu_baskets';
    const MENU_ORDERS = 'menu_orders';
    const PRODUCT_LIST = 'product_list';
    const PRODUCT_EDIT_FORM = 'product_edit_form';
    const PRODUCT_ADD_FORM = 'product_add_form';
    const PRODUCT_LINKED_BASKET_LIST = 'product_linked_basket_list';
    const BASKET_LIST = 'basket_list';
    const BASKET_EDIT_FORM = 'basket_edit_form';
    const BASKET_ADD_FORM = 'basket_add_form';
    const BASKET_ITEM_LIST = 'basket_item_list';
    const BASKET_ITEM_EDIT_FORM = 'basket_item_edit_form';
    const BASKET_ITEM_ADD_FORM = 'basket_item_add_form';
    const SHOP_CONFIG_LIST = 'shop_config_list';
    const SHOP_CONFIG_EDIT_FORM = 'shop_config_edit_form';
    const SHOP_CONFIG_ADD_FORM = 'shop_config_add_form';
    const SHOP_CONFIG_LINKED_BASKET_LIST = 'shop_config_linked_basket_list';
    const ORDER_LIST = 'order_list';
    const ORDER_VIEW_FORM = 'order_view_form';
    const CLIENT_BASKET_LINK = 'client_basket_link';
    const CLIENT_ORDER_LINK = 'client_order_link';
}