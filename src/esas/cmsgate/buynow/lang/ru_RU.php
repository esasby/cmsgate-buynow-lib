<?php

use esas\cmsgate\bridge\dao\OrderStatusBridge;
use esas\cmsgate\buynow\messenger\MessagesBuyNow;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\buynow\view\admin\AdminViewFieldsBuyNow;
use esas\cmsgate\buynow\view\client\ClientViewFieldsBuyNow;

return array(
    AdminViewFieldsBuyNow::MENU_SHOP_CONFIGS => "Настройки магазинов",
    AdminViewFieldsBuyNow::MENU_PRODUCTS => "Товары",
    AdminViewFieldsBuyNow::MENU_BASKETS => "Корзины",
    AdminViewFieldsBuyNow::MENU_ORDERS => "Заказы",
    AdminViewFieldsBuyNow::PRODUCT_LIST => "Список товаров",
    AdminViewFieldsBuyNow::PRODUCT_ADD_FORM => "Добавление товара",
    AdminViewFieldsBuyNow::PRODUCT_EDIT_FORM => "Редактирование товара",
    AdminViewFieldsBuyNow::PRODUCT_LINKED_BASKET_LIST => "Входит в корзины",
    AdminViewFieldsBuyNow::BASKET_LIST => "Список корзин",
    AdminViewFieldsBuyNow::BASKET_ADD_FORM => "Добавление корзины",
    AdminViewFieldsBuyNow::BASKET_EDIT_FORM => "Редактирование корзины",
    AdminViewFieldsBuyNow::BASKET_ITEM_LIST => "Список товаров в корзине",
    AdminViewFieldsBuyNow::BASKET_ITEM_ADD_FORM => "Добавление товара в корзину",
    AdminViewFieldsBuyNow::BASKET_ITEM_EDIT_FORM => "Редактирование товара в корзине",
    AdminViewFieldsBuyNow::SHOP_CONFIG_LIST => "Список магазинов",
    AdminViewFieldsBuyNow::SHOP_CONFIG_ADD_FORM => "Добавление настроек магазина",
    AdminViewFieldsBuyNow::SHOP_CONFIG_EDIT_FORM => "Редактирование настроек магазина",
    AdminViewFieldsBuyNow::SHOP_CONFIG_LINKED_BASKET_LIST => "Используется в корзинах",
    AdminViewFieldsBuyNow::ORDER_LIST => "Список заказов",
    AdminViewFieldsBuyNow::ORDER_VIEW_FORM => "Детали заказа",
    AdminViewFieldsBuyNow::CLIENT_BASKET_LINK => "Купить сейчас! ссылка",
    AdminViewFieldsBuyNow::CLIENT_ORDER_LINK => "Ссылка для клиента",
    OrderStatusBridge::PENDING => "Ожидание оплаты",
    OrderStatusBridge::PAYED => "Оплачен",
    OrderStatusBridge::CANCELED => "Отменен",
    OrderStatusBridge::FAILED => "Произошла ошибка",
    RequestParamsBuyNow::SHOP_CONFIG_NAME => "Название настроек",
    RequestParamsBuyNow::CUSTOMER_EMAIL => 'Email',
    RequestParamsBuyNow::CUSTOMER_FIO => 'Имя и фамилия',
    RequestParamsBuyNow::CUSTOMER_PHONE => 'Номер телефона',
    RequestParamsBuyNow::BASKET_MAX_PAID_COUNT => 'Лимит оплат',
    RequestParamsBuyNow::BASKET_EXPIRES_AT => 'Срок действия',
    RequestParamsBuyNow::BASKET_ASK_NAME => 'Запрашивать имя?',
    RequestParamsBuyNow::BASKET_ASK_EMAIL => 'Запрашивать email?',
    RequestParamsBuyNow::BASKET_ASK_PHONE => 'Запрашивать телефон?',
    RequestParamsBuyNow::BASKET_ACTIVE => 'Корзина активна?',
    MessagesBuyNow::BASKET_INCORRECT_ID => 'Неверный идентификатор корзина',
    MessagesBuyNow::BASKET_IS_INACTIVE => 'Оформление заказа невозможно. Корзина неактивна.',
    MessagesBuyNow::BASKET_IS_EXPIRED => 'Оформление заказа невозможно. Срок жизни корзины истек',
    MessagesBuyNow::BASKET_LIMIT_REACHED => 'Оформление заказа невозможно. Достигнут лимит покупок для данного товара',
    ClientViewFieldsBuyNow::BASKET_PAGE_HEADER => 'Оформление заказа',
    ClientViewFieldsBuyNow::BASKET_PAGE_HEADER_DETAILS => 'Для завершения покупки необходимо оформить заказа',
    ClientViewFieldsBuyNow::HOME_PAGE_HEADER => 'BuyNow Epos',
    ClientViewFieldsBuyNow::HOME_PAGE_HEADER_DETAILS => 'Принимайте QR-платежи просто',
    ClientViewFieldsBuyNow::HOME_PAGE_BUY_NOW_DESCRIPTION => '<p><strong>BuyNow</strong> - простая интеграция Вашего сайта с платежной системой!</p>
<ul>
<li>Нет необходимости перехода на CMS системы с модулями электронной коммерции.</li> 
<li>Подходит для создания заказов со страниц социальных сетях</li>
</ul> 
<p>BuyNow позволят самостоятельно сгенерировать <strong>уникальную ссылку</strong> для оплаты Вашего товара (или группы товаров). Данную ссылку, в виде кнопки, необходимо просто добавить на сайт (страницу) с описанием товара или услуги. </p><p>При нажатии на ссылку клиент будет перенаправлен в корзину с товарами, где закончит оформление заказа и сможет выполнить его оплату. Платежная ссылка также может быть отправлена менеджером клиенту любым удобным способом (по email или в мессенджеры)</p><br/>',
);