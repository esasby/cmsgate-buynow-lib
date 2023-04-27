<?php


namespace esas\cmsgate\buynow\controllers\client;


use esas\cmsgate\buynow\dao\OrderDataBuyNow;
use esas\cmsgate\buynow\BridgeConnectorBuyNow;
use esas\cmsgate\epos\controllers\ControllerEposCompletionPanel;
use esas\cmsgate\epos\controllers\ControllerEposInvoiceAdd;
use esas\cmsgate\hro\pages\ClientOrderCompletionPageHROFactory;
use esas\cmsgate\Registry;
use esas\cmsgate\bridge\service\SessionServiceBridge;
use Exception;
use Throwable;

class ClientControllerBuyNowOrder extends ClientControllerBuyNow
{
    protected $orderId;

    /**
     * ClientControllerBuyNowOrder constructor.
     * @param $orderId
     */
    public function __construct($orderId) {
        parent::__construct();
        $this->orderId = $orderId;
    }

    public function process() {
        $completionPageBuilder = ClientOrderCompletionPageHROFactory::findBuilder();
        try {
            BridgeConnectorBuyNow::fromRegistry()->getOrderCacheService()->loadSessionOrderCacheById($this->orderId);
            $orderWrapper = Registry::getRegistry()->getOrderWrapperForCurrentUser();
            $completionPageBuilder->setOrderWrapper($orderWrapper);
            /** @var OrderDataBuyNow $orderData */
            $orderData = SessionServiceBridge::fromRegistry()::getOrderCacheObj()->getOrderData();
            $basket = BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->getById($orderData->getBasketId());
            $completionPageBuilder->addCssLink($this->getClientUICssLink($basket));
            if ($orderWrapper->getExtId() == null || $orderWrapper->getExtId() == '') {
                $controller = new ControllerEposInvoiceAdd();
                $controller->process($orderWrapper);
            }
            $controller = new ControllerEposCompletionPanel();
            $completionPanel = $controller->process($orderWrapper);
            $completionPageBuilder->setElementCompletionPanel($completionPanel->build());
        } catch (Throwable $e) {
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
        } finally {
            $completionPageBuilder->render();
        }
    }
}