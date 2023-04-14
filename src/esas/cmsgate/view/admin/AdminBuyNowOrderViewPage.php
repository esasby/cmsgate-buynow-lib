<?php


namespace esas\cmsgate\view\admin;


use esas\cmsgate\bridge\OrderCache;
use esas\cmsgate\bridge\OrderDataBuyNow;
use esas\cmsgate\bridge\ShopConfigBuyNow;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\buynow\BuyNowBasket;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\OrderStatusBridge;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactoryCmsGate;
use esas\cmsgate\utils\htmlbuilder\presets\BootstrapPreset as bootstrap;
use esas\cmsgate\utils\htmlbuilder\presets\ScriptsPreset;
use esas\cmsgate\view\RedirectServiceBuyNow;

class AdminBuyNowOrderViewPage extends AdminBuyNowPage
{
    /**
     * @var OrderCache
     */
    private $order;

    /**
     * @var OrderDataBuyNow
     */
    private $orderData;

    /**
     * @var BuyNowBasket
     */
    private $basket;

    /**
     * @var ShopConfigBuyNow
     */
    private $shopConfig;

    /**
     * @param OrderCache $order
     * @return AdminBuyNowOrderViewPage
     */
    public function setOrder($order) {
        $this->order = $order;
        $this->orderData = $order->getOrderData();
        $this->basket = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getById($this->orderData->getBasketId());
        $this->shopConfig = BridgeConnectorBuyNow::fromRegistry()->getShopConfigRepository()->getByUUID($this->order->getShopConfigId());
        return $this;
    }

    public function elementPageHead() {
        $head = parent::elementPageHead();
        $head->add(ScriptsPreset::elementScriptCopyToClipboard());
        return $head;
    }

    public function elementPageContent() {
        return $this->elementClientLinkPanel()
            . element::br()
            . $this->elementOrderViewPanel();
    }

    public function getNavItemId() {
        return RedirectServiceBuyNow::PATH_ADMIN_ORDERS;
    }

    /**
     * @param $basket BuyNowBasket
     * @return string
     */
    private function elementOrderViewPanel() {
        $orderDetailsHRO = HROFactoryCmsGate::fromRegistry()->createCardBuilder()
            ->setCardHeader(AdminViewFieldsBuyNow::ORDER_VIEW_FORM)
            ->setCardBody(HROFactoryCmsGate::fromRegistry()->createDescriptionListBuilder()
                ->setDtDefaultSize(2)
                ->setDdDefaultSize(10)
                ->addDt("Id")
                ->addDd($this->order->getId())
                ->addDt("Shop order id")
                ->addDd($this->orderData->getOrderId())
                ->addDt("Basket")
                ->addDd(bootstrap::elementAHrefNoDecoration($this->basket->getName(), RedirectServiceBuyNow::basketEdit($this->basket->getId())))
                ->addDt("Shop config")
                ->addDd(bootstrap::elementAHrefNoDecoration($this->shopConfig->getName(), RedirectServiceBuyNow::shopConfigEdit($this->shopConfig->getId())))
                ->addDt("Created At")
                ->addDd($this->order->getCreatedAt())
                ->addDt("External Id")
                ->addDd($this->order->getExtId())
                ->addDt("Status")
                ->addDd($this->elementStatus($this->order->getStatus()))
                ->addDt("Client full name")
                ->addDd($this->orderData->getCustomerFIO())
                ->addDt("Client email")
                ->addDd($this->orderData->getCustomerEmail())
                ->addDt("Client phone")
                ->addDd($this->orderData->getCustomerPhone())
                ->addDt("Total")
                ->addDd($this->orderData->getAmount())
                ->addDt("Items")
                ->addDd($this->elementItems())
                ->build())
            ->setCardFooter(HROFactoryCmsGate::fromRegistry()->createCardFooterBuilder()
                ->addButtonCancel(RedirectServiceBuyNow::orderList(false))
                ->build());
        return $orderDetailsHRO->build();
    }

    public function elementStatus($status) {
        $textColorClass = '';
        switch ($status) {
            case OrderStatusBridge::PAYED:
                $textColorClass ='text-success';
                break;
            case OrderStatusBridge::PENDING:
                $textColorClass ='text-warning';
                break;
            case OrderStatusBridge::FAILED:
                $textColorClass ='text-danger';
                break;
            case OrderStatusBridge::CANCELED:
                $textColorClass ='text-secondary';
                break;
        }
        return
            element::span(
                attribute::clazz($textColorClass),
                Translator::fromRegistry()->translate($this->order->getStatus()));
    }

    public function elementItems() {
        $elementItems = "";
        foreach ($this->orderData->getItems() as $item) {
            $elementItems .=
                element::tr(
                    attribute::clazz("position-relative"),
                    element::td($item->getName()),
                    element::td(bootstrap::elementAHrefNoDecoration($item->getSku(), RedirectServiceBuyNow::productEdit($item->getProductId()))),
                    element::td($item->getCount() . ' x ' . $item->getPrice() . " BYN"),
                    element::td($item->getPrice() * $item->getCount())
                );
        }
        return HROFactoryCmsGate::fromRegistry()->createTableBuilder()
            ->setTableBody($elementItems)
            ->build();
    }

    protected function elementClientLinkPanel() {
        return HROFactoryCmsGate::fromRegistry()->createCopyToClipboardPanel()
            ->setLabelId(AdminViewFieldsBuyNow::CLIENT_ORDER_LINK)
            ->setValue(RedirectServiceBuyNow::clientOrderView($this->order->getId()))
            ->build();
    }

    public static function builder() {
        return new AdminBuyNowOrderViewPage();
    }

}