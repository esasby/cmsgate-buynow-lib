<?php


namespace esas\cmsgate\buynow\hro\admin;


use esas\cmsgate\bridge\dao\Order;
use esas\cmsgate\bridge\dao\OrderStatusBridge;
use esas\cmsgate\bridge\dao\ShopConfigRepository;
use esas\cmsgate\buynow\dao\BasketBuyNow;
use esas\cmsgate\buynow\dao\BasketBuyNowRepository;
use esas\cmsgate\buynow\dao\OrderDataBuyNow;
use esas\cmsgate\buynow\dao\ShopConfigBuyNow;
use esas\cmsgate\buynow\service\RedirectServiceBuyNow;
use esas\cmsgate\buynow\view\admin\AdminViewFieldsBuyNow;
use esas\cmsgate\hro\cards\CardFooterHROFactory;
use esas\cmsgate\hro\cards\CardHROFactory;
use esas\cmsgate\hro\panels\CopyToClipboardPanelHROFactory;
use esas\cmsgate\hro\tables\TableHROFactory;
use esas\cmsgate\hro\typography\DescriptionListHROFactory;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\htmlbuilder\presets\BootstrapPreset as bootstrap;
use esas\cmsgate\utils\htmlbuilder\presets\ScriptsPreset;

class AdminBuyNowOrderViewPage extends AdminBuyNowPage
{
    /**
     * @var Order
     */
    private $order;

    /**
     * @var OrderDataBuyNow
     */
    private $orderData;

    /**
     * @var BasketBuyNow
     */
    private $basket;

    /**
     * @var ShopConfigBuyNow
     */
    private $shopConfig;

    /**
     * @param Order $order
     * @return AdminBuyNowOrderViewPage
     */
    public function setOrder($order) {
        $this->order = $order;
        $this->orderData = $order->getOrderData();
        $this->basket = BasketBuyNowRepository::fromRegistry()->getById($order->getBasketId());
        $this->shopConfig = ShopConfigRepository::fromRegistry()->getById($this->order->getShopConfigId());
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
     * @param $basket BasketBuyNow
     * @return string
     */
    private function elementOrderViewPanel() {
        $orderDetailsHRO = CardHROFactory::findBuilder()
            ->setCardHeader(AdminViewFieldsBuyNow::ORDER_VIEW_FORM)
            ->setCardBody(DescriptionListHROFactory::findBuilder()
                ->setDtDefaultSize(2)
                ->setDdDefaultSize(10)
                ->addDt("Id")
                ->addDd($this->order->getId())
                ->addDt("Shop order id")
                ->addDd($this->orderData->getOrderId())
                ->addDt("Basket")
                ->addDd(bootstrap::elementAHrefNoDecoration($this->basket->getName(), RedirectServiceBuyNow::fromRegistry()->basketEdit($this->basket->getId())))
                ->addDt("Shop config")
                ->addDd(bootstrap::elementAHrefNoDecoration($this->shopConfig->getName(), RedirectServiceBuyNow::fromRegistry()->shopConfigEdit($this->shopConfig->getId())))
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
            ->setCardFooter(CardFooterHROFactory::findBuilder()
                ->addButtonCancel(RedirectServiceBuyNow::fromRegistry()->orderList(false))
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
                    element::td(bootstrap::elementAHrefNoDecoration($item->getSku(), RedirectServiceBuyNow::fromRegistry()->productEdit($item->getProductId()))),
                    element::td($item->getCount() . ' x ' . $item->getPrice() . " BYN"),
                    element::td($item->getPrice() * $item->getCount())
                );
        }
        return TableHROFactory::findBuilder()
            ->setTableBody($elementItems)
            ->build();
    }

    protected function elementClientLinkPanel() {
        return CopyToClipboardPanelHROFactory::findBuilder()
            ->setLabelId(AdminViewFieldsBuyNow::CLIENT_ORDER_LINK)
            ->setValue(RedirectServiceBuyNow::fromRegistry()->clientOrderView($this->order->getId()))
            ->build();
    }

    public static function builder() {
        return new AdminBuyNowOrderViewPage();
    }

}