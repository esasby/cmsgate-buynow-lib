<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\bridge\OrderCache;
use esas\cmsgate\bridge\OrderDataBuyNow;
use esas\cmsgate\bridge\ShopConfigBuyNow;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactoryCmsGate;
use esas\cmsgate\utils\htmlbuilder\presets\TablePreset;
use esas\cmsgate\view\RedirectServiceBuyNow;

class AdminBuyNowOrderListPage extends AdminBuyNowPage
{
    private $orderList;

    /**
     * @param OrderCache[] $orderList
     * @return AdminBuyNowOrderListPage
     */
    public function setOrderList($orderList) {
        $this->orderList = $orderList;
        return $this;
    }

    public function elementPageContent() {
        return
            HROFactoryCmsGate::fromRegistry()->createDataListBuilder()
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
     * @param OrderCache $order
     */
    public function elementOrdersTableRow($order, $rowId) {
        /** @var OrderDataBuyNow $orderData */
        $orderData = $order->getOrderData();
        $basket = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getById($orderData->getBasketId());
        /** @var ShopConfigBuyNow $shopConfig */
        $shopConfig = BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository()->getByUUID($order->getShopConfigId());
        return element::tr(
            attribute::clazz("position-relative"),
            element::td(TablePreset::elementTdStretchedLink($order->getId(), RedirectServiceBuyNow::orderView($order->getId()))),
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