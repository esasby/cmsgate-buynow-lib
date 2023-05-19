<?php


namespace esas\cmsgate\buynow\hro\admin;


use esas\cmsgate\bridge\dao\Order;
use esas\cmsgate\bridge\dao\ShopConfigRepository;
use esas\cmsgate\buynow\dao\BasketBuyNowRepository;
use esas\cmsgate\buynow\dao\OrderDataBuyNow;
use esas\cmsgate\buynow\dao\ShopConfigBuyNow;

use esas\cmsgate\buynow\view\admin\AdminViewFieldsBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\hro\tables\DataListHROFactory;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;

class AdminBuyNowOrderListPage extends AdminBuyNowPage
{
    private $orderList;

    /**
     * @param Order[] $orderList
     * @return AdminBuyNowOrderListPage
     */
    public function setOrderList($orderList) {
        $this->orderList = $orderList;
        return $this;
    }

    public function elementPageContent() {
        return
            DataListHROFactory::findBuilder()
                ->setMainLabel(AdminViewFieldsBuyNow::ORDER_LIST)
                ->setTableHeaderColumns(['Id', 'Order Id', 'Basket', 'Shop config', 'Total', 'Currency', 'Status', 'Created At'])
                ->setTableBody($this->elementOrdersTableBody())
                ->build();
    }

    public function elementOrdersTableBody() {
        $rows = '';
        foreach ($this->orderList as $key => $value) {
            $rows .= $this->elementOrdersTableRow($value, $key);
        }
        return element::tbody($rows);
    }

    /**
     * @param Order $order
     */
    public function elementOrdersTableRow($order, $rowId) {
        /** @var OrderDataBuyNow $orderData */
        $orderData = $order->getOrderData();
        $basket = BasketBuyNowRepository::fromRegistry()->getById($order->getBasketId());
        /** @var ShopConfigBuyNow $shopConfig */
        $shopConfig = ShopConfigRepository::fromRegistry()->getById($order->getShopConfigId());
        return element::tr(
            attribute::clazz("position-relative"),
            element::td(TablePreset::elementTdStretchedLink($order->getId(), RedirectServiceBuyNow::fromRegistry()->orderView($order->getId()))),
            element::td($orderData->getOrderId()),
            element::td($basket->getName()),
            element::td($shopConfig->getName()),
            element::td($orderData->getAmount()),
            element::td($orderData->getCurrency()),
            element::td(Translator::fromRegistry()->translate($order->getStatus())),
            element::td($order->getCreatedAt())
        );
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_ORDERS;
    }

    public static function builder() {
        return new AdminBuyNowOrderListPage();
    }
}